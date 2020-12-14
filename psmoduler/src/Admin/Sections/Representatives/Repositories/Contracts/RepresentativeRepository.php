<?php
namespace Psmoduler\Admin\Sections\Representatives\Repositories\Contracts;

interface RepresentativeRepository
{
    /**
     * Find one by code
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return array|null
     */
    public function findOneByCode($code);
}
