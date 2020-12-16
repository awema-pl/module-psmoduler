<?php
/**
 * The MIT License (MIT)
 *
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

use Psmoduler\Admin\Sections\Representatives\Http\Controllers\RepresentativeController;
use Psmoduler\Admin\Sections\Installations\Services\Menu\Contracts\TabBuilder as TabBuilderContract;
use Psmoduler\Admin\Sections\Installations\Services\Menu\TabBuilder;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\RepresentativeInstaller as RepresentativeInstallerContract;
use Psmoduler\Admin\Sections\Installations\Services\Database\RepresentativeInstaller;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\ProfileInstaller as ProfileInstallerContract;
use Psmoduler\Admin\Sections\Installations\Services\Database\ProfileInstaller;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\AccessInstaller as AccessInstallerContract;
use Psmoduler\Admin\Sections\Installations\Services\Database\AccessInstaller;

class Psmoduler extends Module
{
    /** @var TabBuilderContract $tabBuilder */
    protected $tabBuilder;

    /** @var RepresentativeInstallerContract $representativeInstaller */
    protected $representativeInstaller;

    /** @var ProfileInstallerContract $profileInstaller */
    protected $profileInstaller;

    /** @var AccessInstallerContract $accessInstaller */
    protected $accessInstaller;

    public function __construct()
    {
        $this->tabBuilder = new TabBuilder($this);
        $this->representativeInstaller = new RepresentativeInstaller($this);
        $this->profileInstaller = new ProfileInstaller($this);
        $this->accessInstaller = new AccessInstaller($this);
        $this->name = 'psmoduler';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Awema';
        $this->need_instance = true;
        $this->ps_versions_compliancy = [
            'min' => '1.7.6.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->trans('Psmoduler', [], 'Modules.Psmoduler.Psmoduler');
        $this->description = $this->trans('PrestaShop module from Awema.', [], 'Modules.Psmoduler.Psmoduler');
        $this->confirmUninstall = $this->trans('Are you sure you want to uninstall?', [], 'Modules.Psmoduler.Psmoduler');
    }

    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink(RepresentativeController::TAB_CLASS_NAME)
        );
    }

    public function install()
    {
        return $this->representativeInstaller->install()
            && $this->profileInstaller->install()
            && $this->accessInstaller->install()
            && parent::install()
            && $this->installTabs();
    }

    public function uninstall()
    {
        return $this->representativeInstaller->uninstall()
            && $this->profileInstaller->uninstall()
            && $this->accessInstaller->uninstall()
            && parent::uninstall()
            && $this->uninstallTabs();
    }

    /**
     * Install tabs
     *
     * @return bool
     */
    private function installTabs()
    {
        return $this->tabBuilder->addTab([
                'controller_class_name' => 'Parent' . RepresentativeController::TAB_CLASS_NAME,
                'name_id' => 'Sales representatives',
                'icon' => 'business_center',
                'module_name' => $this->name,
                'position' => 0,
                'parent_controller_class_name' => 'IMPROVE',
            ])
            && $this->tabBuilder->addTab([
                'controller_class_name' => RepresentativeController::TAB_CLASS_NAME,
                'name_id' => 'Representatives',
                'icon' => 'business_center',
                'module_name' => $this->name,
                'position' => 50,
                'parent_controller_class_name' => 'Parent' . RepresentativeController::TAB_CLASS_NAME,
                'route_name' => 'psmoduler_admin_representatives_index',
            ]);
    }

    /**
     * Unnstall tabs
     *
     * @return bool
     */
    private function uninstallTabs()
    {
        return $this->tabBuilder->deleteTab([
                'controller_class_name' => RepresentativeController::TAB_CLASS_NAME,
            ]) &&
            $this->tabBuilder->deleteTab([
                'controller_class_name' => 'Parent' . RepresentativeController::TAB_CLASS_NAME,
            ]);
    }
}
