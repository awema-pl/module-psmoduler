<?php

namespace Psmoduler\Admin\Sections\Representatives\Toolbars;

use PrestaShopBundle\Service\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;

class RepresentativeToolbar
{
    /** @var TranslatorInterface $translator */
    private $translator;
    
    /** @var Router $router */
    private $router;

    public function __construct(TranslatorInterface $translator, Router $router)
    {
        $this->translator = $translator;
        $this->router = $router;
    }

    public function getToolbarButtons()
    {
        return [
            'add' => [
                'desc' => $this->translator->trans('Add new representative', [], 'Modules.Psmoduler.Admin'),
                'icon' => 'add_circle_outline',
                'href' => $this->router->generate('psmoduler_admin_representatives_create'),
            ],
            'assign' => [
                'desc' => $this->translator->trans('Assign representative', [], 'Modules.Psmoduler.Admin'),
                'icon' => 'folder_shared',
                'href' => $this->router->generate('psmoduler_admin_representatives_assign'),
            ],
        ];
    }
}
