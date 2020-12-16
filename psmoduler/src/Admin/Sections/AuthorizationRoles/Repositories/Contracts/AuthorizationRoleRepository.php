<?php
namespace Psmoduler\Admin\Sections\AuthorizationRoles\Repositories\Contracts;

interface AuthorizationRoleRepository
{
    /**
     * Get ID authorization tole by slug
     *
     * @param $slug
     * @return int
     */
    public function getIdBySlug($slug);
}
