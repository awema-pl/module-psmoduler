<?php

namespace Psmoduler\Admin\Sections\Representatives\Repositories;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository as RepresentativeRepositoryContract;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;

class RepresentativeRepository extends EntityRepository implements RepresentativeRepositoryContract
{
    /**
     * Find one by code
     *
     * @param array $conditions
     * @param array $columns
     *
     * @return array|null
     */
    public function findOneByCode($code)
    {
        return $this->findOneBy(['code'=>$code]);
        
//        /** @var QueryBuilder $qb */
//        $qb = $this->createQueryBuilder('q')
//        ->select('q.code')
//        ->where('q.code = :code')
//        ->setParameter('code', $code);
//        return $qb->getQuery()
//            ->getSingleResult();
    }

}
