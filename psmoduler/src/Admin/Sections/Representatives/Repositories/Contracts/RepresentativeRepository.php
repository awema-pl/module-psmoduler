<?php
namespace Psmoduler\Admin\Sections\Representatives\Repositories\Contracts;

interface RepresentativeRepository
{
    /**
     * Get by code
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return array|null
     */
    public function getByCode($code);
}
