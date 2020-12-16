<?php
namespace Psmoduler\Admin\Sections\Profiles\Repositories\Contracts;

interface ProfileRepository
{
    /**
     * Find
     *
     * @param int $id
     * @return array
     */
    public function find($id);
        
    /**
     * Exist
     *
     * @param string $name
     * @return bool
     */
    public function exist($name);

    /**
     * Delete by name
     *
     * @param string $name
     * @return bool
     */
    public function deleteByName($name);
    
    /**
     * Add profile
     *
     * @param array $names
     * @return int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function add($names);

    /**
     * Get ID profile by name
     *
     * @param string $name
     * @return int
     */
    public function getIdProfileByName($name);
}
