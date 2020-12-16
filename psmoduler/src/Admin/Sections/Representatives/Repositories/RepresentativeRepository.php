<?php

namespace Psmoduler\Admin\Sections\Representatives\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository as RepresentativeRepositoryContract;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;
use Db;

class RepresentativeRepository extends EntityRepository implements RepresentativeRepositoryContract
{
    /**
     * Find one by code
     *
     * @param string $code
     * @return object|null
     */
    public function findOneByCode($code)
    {
        return $this->findOneBy(['code'=>$code]);
    }

    /**
     * Find one by ID employee
     *
     * @param int $idEmployee
     * @return object|null
     */
    public function findOneByIdEmployee($idEmployee)
    {
        return $this->findOneBy(['idEmployee'=>$idEmployee]);
    }

    /**
     * Get employee ID's
     *
     * @return array
     */
    public function getEmployeeIds()
    {
       return array_column($this->createQueryBuilder('r')->select('r.idEmployee')
           ->getQuery()->getResult(), 'idEmployee');
    }

}
