<?php
/**
 * The MIT License (MIT)
 *
 * @author    Awema <developer@awema.pl>
 * @copyright Copyright (c) 2020 Awema
 * @license   MIT License
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
if (!defined('_PS_VERSION_')) {
    exit;
}
// Needed for install process
require_once __DIR__ . '/vendor/autoload.php';

use Psmoduler\Controller\Admin\RepresentativeController;
use Psmoduler\Service\Module\AdminTabInterface;
use Psmoduler\Service\Module\AdminTab;

class Psmoduler extends Module
{
    /** @var AdminTabInterface */
    protected $adminTab;

    public function __construct()
    {
        $this->adminTab = new AdminTab($this);
        $this->name = 'psmoduler';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Awema';
        $this->need_instance = true;
        $this->ps_versions_compliancy = [
            'min' => '1.7.6',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->trans('Psmoduler', [], 'Modules.Psmoduler.Psmoduler');
        $this->description = $this->trans('PrestaShop module from Awema.', [], 'Modules.Psmoduler.Psmoduler');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Psmoduler.Psmoduler');
        // See https://devdocs.prestashop.com/1.7/modules/concepts/controllers/admin-controllers/tabs/
        $tabNames = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tabNames[$lang['locale']] = $this->trans('Demo Controller Tabs', [], 'Modules.Psmoduler.Psmoduler', $lang['locale']);
        }
//        $this->tabs = [
//            [
//                'route_name' => 'psmoduler_admin_salesrepresentative_index',
//                'class_name' => SalesRepresentativeController::TAB_CLASS_NAME,
//                'visible' => true,
//                'name' => $tabNames,
//                'icon' => 'school',
//                'parent_class_name' => 'SELL',
//            ],
//        ];
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function getContent()
    {
        // This uses the matching with the route ps_controller_tabs_configure via the _legacy_link property
        // See https://devdocs.prestashop.com/1.7/development/architecture/migration-guide/controller-routing
//        Tools::redirectAdmin(
//            $this->context->link->getAdminLink(DashboardController::TAB_CLASS_NAME)
//        );
    }

    public function install()
    {
        return parent::install() && $this->installTabs();
    }

    /**
     * Install tabs
     *
     * @return bool
     */
    private function installTabs()
    {
        return $this->adminTab->addTab([
                'controller_class_name' => 'Parent' . RepresentativeController::TAB_CLASS_NAME,
                'name_id' => 'Sales representatives',
                'icon' => 'business_center',
                'module_name' => $this->name,
                'position' => 0,
                'parent_controller_class_name' => 'IMPROVE',
            ])
            && $this->adminTab->addTab([
                'controller_class_name' => RepresentativeController::TAB_CLASS_NAME,
                'name_id' => 'Representatives',
                'icon' => 'business_center',
                'module_name' => $this->name,
                'position' => 50,
                'parent_controller_class_name' => 'Parent' . RepresentativeController::TAB_CLASS_NAME,
                'route_name' => 'psmoduler_admin_representative_index',
            ]);
    }
}
