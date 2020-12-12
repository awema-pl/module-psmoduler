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

namespace Psmoduler\Service\Admin;

use Psmoduler\Exception\PsmodulerException;
use Symfony\Component\Translation\TranslatorInterface;
use Tab;
use Language;
use Module;

class TabBuilder
{
    /** @var TranslatorInterface $translator */
    private $translator;

    public function __construct(TranslatorInterface $translator){
        $this->translator = $translator;
    }

    /**
     * Add tab
     *
     * @param $module
     * @param array $config = [
     *      'fields' => [
     *          'controller_class_name',
     *          'name_id',
     *          'icon',
     *          'parent_controller_class_name',
     *          'module_name',
     *          'route_name',
     *          'position',
     *      ]
     * ]
     * @return bool
     */
    public function addTab($config)
    {
        if (!isset($config['controller_class_name'])) {
            throw new PsmodulerException('Not fount "controller_class_name" in configuration.');
        }
        else if (!isset($config['name_id'])) {
            throw new PsmodulerException('Not fount "name_id" in configuration.');
        }
        else if (!isset($config['module_name'])) {
            throw new PsmodulerException('Not fount "module_name" in configuration.');
        }
        else if (!isset($config['icon'])) {
            throw new PsmodulerException('Not fount "icon" in configuration.');
        }
        $controllerClassName = $config['controller_class_name'];
        $tabId = (int)Tab::getIdFromClassName($controllerClassName);
        if (!$tabId) {
            $tabId = null;
        }
        $tab = new Tab($tabId);
        $tab->active = isset($config['active']) ? $config['active'] : 1;
        $tab->class_name = $controllerClassName;
        if ($routeName = isset($config['route_name']) ? $config['route_name'] : null) {
            $tab->route_name = $routeName;
        }
        $tab->name = [];
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = $this->translator->trans($config['name_id'], [], 'Modules.Psmoduler.Psmoduler', $lang['locale']);
        }
        $tab->icon = $config['icon'];
        if ($parentControllerClassName = isset($config['parent_controller_class_name']) ? $config['parent_controller_class_name'] : null) {
            $tab->id_parent = (int)Tab::getIdFromClassName($parentControllerClassName);
        }
        $tab->module = $config['module_name'];
        $saved = (bool)$tab->save();
        if ($saved) {
            if (isset($config['position'])) {
                $tab->updatePosition(false, (int)$config['position']);
            }
        }
        return $saved;
    }

    /**
     * Delete tab
     *
     * @param $module
     * @param array $config = [
     *      'fields' => [
     *          'controller_class_name',
     *      ]
     * ]
     * @return bool
     */
    public function deleteTab($config)
    {
        if (!isset($config['controller_class_name'])) {
            throw new PsmodulerException('Not fount "controller_class_name" in configuration.');
        }
        $controllerClassName = $config['controller_class_name'];
        $idTab = Tab::getIdFromClassName($controllerClassName);
        if ($idTab) {
            $tab = new Tab($idTab);
            return $tab->delete();
        }
        return false;
    }
}
