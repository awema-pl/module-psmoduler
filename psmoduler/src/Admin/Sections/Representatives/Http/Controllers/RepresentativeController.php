<?php
/**
 * The MIT License (MIT)
 *
 *  @author    Awema <developer@awema.pl>
 *  @copyright Copyright (c) 2020 Awema
 *  @license   MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Http\Controllers;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Psmoduler\Admin\Sections\Representatives\Grids\Filters\RepresentativeFilters;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;
use Psmoduler\Admin\Sections\Representatives\Toolbars\RepresentativeToolbar;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class RepresentativeController extends FrameworkBundleAdminController
{
    const TAB_CLASS_NAME = 'PsmodulerAdminRepresentativesRepresentative';

    /**
     * List representatives
     *
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     * @param RepresentativeFilters $filters
     * @return Response
     */
    public function index(RepresentativeFilters $filters)
    {
        $representativeGridFactory = $this->get('psmoduler.admin.representatives.grids.factories.representatives');
        $representativeGrid = $representativeGridFactory->getGrid($filters);
        $toolbar = $this->get('psmoduler.admin.representatives.toolbars.representative');

        return $this->render('@Modules/psmoduler/resources/views/admin/sections/representatives/index.html.twig', [
            'enableSidebar' => true,
            'layoutTitle' => $this->trans('Representatives', 'Modules.Psmoduler.Admin'),
            'layoutHeaderToolbarBtn' => $toolbar->getToolbarButtons(),
            'representativeGrid' => $this->presentGrid($representativeGrid),
        ]);

    }


    /**
     * Create representative
     *
     * @AdminSecurity("is_granted('create', request.get('_legacy_controller'))")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $representativeFormBuilder = $this->get('psmoduler.admin.representatives.forms.identifiable_object.builder.representative_form_builder');
        $representativeForm = $representativeFormBuilder->getForm();
        $representativeForm->handleRequest($request);

        $representativeFormHandler = $this->get('psmoduler.admin.representatives.forms.identifiable_object.handler.representative_form_handler');
        $result = $representativeFormHandler->handle($representativeForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('psmoduler_admin_representatives_index');
        }

        return $this->render('@Modules/psmoduler/resources/views/admin/sections/representatives/create.html.twig', [
            'representativeForm' => $representativeForm->createView(),
        ]);
    }

    /**
     * Edit representatives
     *
     * @AdminSecurity("is_granted('update', request.get('_legacy_controller'))")
     * @param Request $request
     * @param int $idRepresentative
     * @return Response
     */
    public function edit(Request $request, $idRepresentative)
    {
        $representativeFormBuilder = $this->get('psmoduler.admin.representatives.forms.identifiable_object.builder.representative_form_builder');
        $representativeForm = $representativeFormBuilder->getFormFor((int) $idRepresentative);
        $representativeForm->handleRequest($request);

        $representativeFormHandler = $this->get('psmoduler.admin.representatives.forms.identifiable_object.handler.representative_form_handler');
        $result = $representativeFormHandler->handleFor((int) $idRepresentative, $representativeForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $this->redirectToRoute('psmoduler_admin_representatives_index');
        }

        return $this->render('@Modules/psmoduler/resources/views/admin/sections/representatives/edit.html.twig', [
            'representativeForm' => $representativeForm->createView(),
        ]);
    }

    /**
     * Delete bulk representatives
     *
     * @AdminSecurity("is_granted('delete', request.get('_legacy_controller'))")
     * @param Request $request
     * @return Response
     */
    public function deleteBulk(Request $request)
    {
        $representativeIds = $request->request->get('representative_bulk');
        $repository = $this->get('psmoduler.admin.representatives.repositories.representative');
        try {
            $representatives = $repository->findById($representativeIds);
        } catch (EntityNotFoundException $e) {
            $representatives = null;
        }
        if (!empty($representatives)) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            foreach ($representatives as $representative) {
                $em->remove($representative);
            }
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('The selection has been successfully deleted.', 'Admin.Notifications.Success')
            );
        }

        return $this->redirectToRoute('psmoduler_admin_representatives_index');
    }
}
