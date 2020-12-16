<?php
namespace Psmoduler\Admin\Sections\Accesses\Repositories\Contracts;

interface AccessRepository
{
    /**
     * Exist access
     *
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return bool
     */
    public function exist($idAuthorizationRole, $idProfile);

    /**
     * Delete access by name
     *
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return bool
     */
    public function delete($idAuthorizationRole, $idProfile);
    
    /**
     * Add access
     *
     * @param $name
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function add($idAuthorizationRole, $idProfile);
}
