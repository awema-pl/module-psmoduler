<?php
/**
 * The MIT License (MIT)
 *
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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use Psmoduler\Admin\Sections\Representatives\Grids\Definitions\Factories\RepresentativeGridDefinitionFactory;
use Psmoduler\Admin\Sections\Representatives\Grids\Filters\RepresentativeFilters;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;
use Psmoduler\Admin\Sections\Representatives\Toolbars\RepresentativeToolbar;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * Provides filters functionality.
     *
     * @AdminSecurity("is_granted('read', request.get('_legacy_controller'))")
     * @param Request $request
     * @return RedirectResponse
     */
    public function search(Request $request)
    {
        /** @var ResponseBuilder $responseBuilder */
        $responseBuilder = $this->get('prestashop.bundle.grid.response_builder');

        return $responseBuilder->buildSearchResponse(
            $this->get('psmoduler.admin.representatives.grids.definitions.factories.representatives'),
            $request,
            RepresentativeGridDefinitionFactory::GRID_ID,
            'psmoduler_admin_representatives_index'
        );
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
     * Assign representative
     *
     * @AdminSecurity("is_granted('create', request.get('_legacy_controller'))")
     * @param Request $request
     * @return Response
     */
    public function assign(Request $request)
    {
        $assignRepresentativeFormBuilder = $this->get('psmoduler.admin.representatives.forms.identifiable_object.builder.assign_representative_form_builder');
        $assignRepresentativeForm = $assignRepresentativeFormBuilder->getForm();
        $assignRepresentativeForm->handleRequest($request);

        $assignRepresentativeFormHandler = $this->get('psmoduler.admin.representatives.forms.identifiable_object.handler.assign_representative_form_handler');
        $result = $assignRepresentativeFormHandler->handle($assignRepresentativeForm);

        if (null !== $result->getIdentifiableObjectId()) {
            $this->addFlash(
                'success',
                $this->trans('Successful creation.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('psmoduler_admin_representatives_index');
        }

        return $this->render('@Modules/psmoduler/resources/views/admin/sections/representatives/assign.html.twig', [
            'assignRepresentativeForm' => $assignRepresentativeForm->createView(),
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
        $representativeForm = $representativeFormBuilder->getFormFor((int) $idRepresentative, [], [
            'id_representative' =>(int) $idRepresentative,
            'is_for_editing' => true,
        ]);
        $representativeForm->handleRequest($request);

        $representativeFormHandler = $this->get('psmoduler.admin.representatives.forms.identifiable_object.handler.representative_form_handler');
        $result = $representativeFormHandler->handleFor((int) $idRepresentative, $representativeForm);

        if ($result->isSubmitted() && $result->isValid()) {
            $this->addFlash('success', $this->trans('Successful update.', 'Admin.Notifications.Success'));

            return $this->redirectToRoute('psmoduler_admin_representatives_index');
        }

        return $this->render('@Modules/psmoduler/resources/views/admin/sections/representatives/edit.html.twig', [
            'representativeForm' => $representativeForm->createView()
        ]);
    }


    /**
     * Delete representative
     *
     * @AdminSecurity("is_granted('delete', request.get('_legacy_controller'))")
     * @param int $idRepresentative
     * @return Response
     */
    public function delete($idRepresentative)
    {
        $repository = $this->get('psmoduler.admin.representatives.repositories.representative');
        try {
            $representative = $repository->findOneById($idRepresentative);
        } catch (EntityNotFoundException $e) {
            $representative = null;
        }

        if (null !== $representative) {
            /** @var EntityManagerInterface $em */
            $em = $this->get('doctrine.orm.entity_manager');
            $em->remove($representative);
            $em->flush();

            $this->addFlash(
                'success',
                $this->trans('Successful deletion.', 'Admin.Notifications.Success')
            );
        } else {
            $this->addFlash(
                'error',
                $this->trans(
                    'Cannot find representative %representative%',
                    'Modules.Psmoduler.Admin',
                    ['%representative%' => $idRepresentative]
                )
            );
        }

        return $this->redirectToRoute('psmoduler_admin_representatives_index');
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
