<?php
namespace Psmoduler\Admin\Sections\Employees\Repositories\Contracts;

interface EmployeeRepository
{
    /**
     * Get employees for choices
     *
     * @return array
     */
    public function getEmployeesForChoices();

    /**
     * Find employee
     *
     * @param int $id
     * @return array
     */
    public function find($id);

    /**
     * Find one employee by email
     *
     * @param string $email
     * @return array
     */
    public function findOneByEmail($email);
}
