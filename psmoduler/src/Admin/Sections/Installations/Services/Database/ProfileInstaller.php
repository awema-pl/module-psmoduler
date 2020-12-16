<?php

namespace Psmoduler\Admin\Sections\Installations\Services\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Exception;
use Psmoduler\Admin\Sections\Commons\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\ProfileInstaller as ProfileInstallerContract;
use Context;
use Module;
use Profile;
use Psmoduler\Admin\Sections\Profiles\Repositories\Contracts\ProfileRepository as ProfileRepositoryContract;
use Psmoduler\Admin\Sections\Profiles\Repositories\ProfileRepository;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Language;

class ProfileInstaller implements ProfileInstallerContract
{
    const PROFILE_NAME = 'Sales representative';

    /** @var Module $module */
    private $module;

    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Install
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function install()
    {
        $this->uninstall();
        $connection = $this->module->get('doctrine.dbal.default_connection');
        $repository = new ProfileRepository($connection);
        if (!$repository->exist(self::PROFILE_NAME)) {
            $names = [];
            foreach (Language::getLanguages(false) as $lang) {
                $names[$lang['id_lang']] = $this->module->getTranslator()->trans(self::PROFILE_NAME, [], 'Modules.Psmoduler.Admin', $lang['locale']);
            }
            return !!$repository->add($names);
        }
        return true;
    }

    /**
     * Uninstall
     *
     * @return bool
     * @throws DBALException
     */
    public function uninstall()
    {
        $connection = $this->module->get('doctrine.dbal.default_connection');
        $repository = new ProfileRepository($connection);
        if ($repository->exist(self::PROFILE_NAME)) {
            return $repository->deleteByName(self::PROFILE_NAME);
        }
        return true;
    }



}
