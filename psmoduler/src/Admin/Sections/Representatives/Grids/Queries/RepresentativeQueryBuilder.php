<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Grids\Queries;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class RepresentativeQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $searchCriteriaApplicator;

    /**
     * @var int
     */
    private $languageId;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator
     * @param int $languageId
     */
    public function __construct(Connection $connection, $dbPrefix, DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator, $languageId)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
        $this->languageId = $languageId;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());
        $this->searchCriteriaApplicator
            ->applySorting($searchCriteria, $qb)
            ->applyPagination($searchCriteria, $qb);
        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(DISTINCT r.id_representative)');
        return $qb;
    }

    /**
     * Get generic query builder.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filters)
    {
        $allowedFilters = [
            'id_representative',
            'code',
            'phone',
            'id_employee'
        ];
        $qb = $this->connection
            ->createQueryBuilder()
            ->select('r.id_representative, r.code, r.phone, r.id_employee, e.id_employee, e.firstname as employeeFirstname, e.lastname as employeeLastname, e.email as employeeEmail')
            ->from($this->dbPrefix . 'psmoduler_representative', 'r')
            ->innerJoin('r', _DB_PREFIX_ . 'employee', 'e', 'r.id_employee = e.id_employee');
        foreach ($filters as $name => $value) {
            if (!in_array($name, $allowedFilters, true)) {
                continue;
            }
            if ('id_representative' === $name) {
                $qb->andWhere('r.`id_representative` = :' . $name);
                $qb->setParameter($name, $value);
                continue;
            }
            else if ('id_employee' === $name) {
                $orX = $qb->expr()->orX();
                $orX->add($qb->expr()->eq('r.`id_employee`', ':' . $name));
                $qb->setParameter($name, $value);
                foreach (explode(' ', $value) as $word){
                    $orX->add($qb->expr()->like('e.`firstname`', ':' . $name));
                    $orX->add($qb->expr()->like('e.`lastname`', ':' . $name));
                    $orX->add($qb->expr()->like('e.`email`', ':' . $name));
                    $qb->setParameter($name, '%'.$word.'%');
                }
                $qb->andWhere($orX);
                continue;
            }
            $qb->andWhere("$name LIKE :$name");
            $qb->setParameter($name, '%' . $value . '%');
        }
        return $qb;
    }
}
