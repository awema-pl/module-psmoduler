<?php

namespace Psmoduler\Admin\Sections\Installations\Services\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Exception;
use Psmoduler\Admin\Sections\Accesses\Repositories\AccessRepository;
use Psmoduler\Admin\Sections\AuthorizationRoles\Repositories\AuthorizationRoleRepository;
use Psmoduler\Admin\Sections\Commons\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\AccessInstaller as AccessInstallerContract;
use Context;
use Module;
use Profile;
use Language;
use Psmoduler\Admin\Sections\Profiles\Repositories\ProfileRepository;

class AccessInstaller implements AccessInstallerContract
{
    const AUTHORIZATION_ROLE_SLUGS =[
        'ROLE_MOD_TAB_ADMINDASHBOARD_READ'
    ];
    
    /** @var Module $module */
    private $module;
    
    public function __construct(Module $module){
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
        $profileRepository = new ProfileRepository($connection);
        $idProfile = $profileRepository->getIdProfileByName(ProfileInstaller::PROFILE_NAME);
        $authorizationRoleRepository = new AuthorizationRoleRepository($connection);
        $accessRepository = new AccessRepository($connection);

        foreach (self::AUTHORIZATION_ROLE_SLUGS as $aurorizationRoleSlug){
            $idAuthorizationRole = $authorizationRoleRepository->getIdBySlug($aurorizationRoleSlug);
            if (!$accessRepository->exist($idAuthorizationRole, $idProfile)){
                $accessRepository->add($idAuthorizationRole, $idProfile);
            }
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
        $profileRepository = new ProfileRepository($connection);
        $idProfile = $profileRepository->getIdProfileByName(ProfileInstaller::PROFILE_NAME);
        $authorizationRoleRepository = new AuthorizationRoleRepository($connection);
        $accessRepository = new AccessRepository($connection);
        foreach (self::AUTHORIZATION_ROLE_SLUGS as $aurorizationRoleSlug){
            $idAuthorizationRole = $authorizationRoleRepository->getIdBySlug($aurorizationRoleSlug);
            if ($accessRepository->exist($idAuthorizationRole, $idProfile)){
                $accessRepository->delete($idAuthorizationRole, $idProfile);
            }
        }
        return true;
    }

}
