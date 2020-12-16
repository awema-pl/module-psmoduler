<?php

namespace Psmoduler\Admin\Sections\Employees\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShopBundle\Security\Admin\Employee;
use Psmoduler\Admin\Sections\Employees\Repositories\Contracts\EmployeeRepository as EmployeeRepositoryContract;

class EmployeeRepository implements EmployeeRepositoryContract
{

    /** @var Connection $connection */
    private $connection;
    
    /** @var $idShop */
    private $idShop;

    public function __construct(Connection $connection, $idShop)
    {
        $this->connection = $connection;
        $this->idShop = $idShop;
    }

    /**
     * Get employees for choices
     *
     * @return array
     */
    public function getEmployeesForChoices()
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('e.id_employee, e.firstname, e.lastname, e.email, s.id_employee, s.id_shop')
            ->from(_DB_PREFIX_.'employee', 'e')
            ->innerJoin('e', _DB_PREFIX_.'employee_shop', 's', 'e.id_employee = s.id_employee')
            ->where('id_shop = :id_shop' )
            ->setParameter('id_shop', $this->idShop)
            ->execute();
        return $sql->fetchAll();
    }

    /**
     * Find employee
     *
     * @param int $id
     * @return array
     */
    public function find($id)
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(_DB_PREFIX_.'employee')
            ->where('id_employee = :id')
            ->setParameter('id', $id)
            ->execute();
        return $sql->fetch();
    }

    /**
     * Find one employee by email
     *
     * @param string $email
     * @return array
     */
    public function findOneByEmail($email)
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(_DB_PREFIX_.'employee')
            ->where('email = :email')
            ->setParameter('email', $email)
            ->execute();
        return $sql->fetch();
    }
}
