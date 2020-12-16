<?php
namespace Psmoduler\Admin\Sections\Representatives\Repositories\Contracts;

interface RepresentativeRepository
{
    /**
     * Find one by code
     *
     * @param string $code
     * @return object|null
     */
    public function findOneByCode($code);

    /**
     * Find one by ID employee
     *
     * @param int $idEmployee
     * @return object|null
     */
    public function findOneByIdEmployee($idEmployee);

    /**
     * Get employee ID's
     *
     * @return array
     */
    public function getEmployeeIds();
}
