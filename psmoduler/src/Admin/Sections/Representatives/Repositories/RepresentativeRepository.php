<?php

namespace Psmoduler\Admin\Sections\Representatives\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository as RepresentativeRepositoryContract;

class RepresentativeRepository extends EntityRepository implements RepresentativeRepositoryContract
{
    /**
     * Get by code
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return array|null
     */
    public function getByCode($code)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->createQueryBuilder('q')
        ->select('q.code')
        ->where('q.code = :code')
        ->setParameter('code', $code);
        return $qb->getQuery()
            ->getResult();
    }

    public function getData()
    {
        return ['wef'];
    }
}
