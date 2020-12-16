<?php

namespace Psmoduler\Admin\Sections\AuthorizationRoles\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShopBundle\Security\Admin\Employee;
use Psmoduler\Admin\Sections\AuthorizationRoles\Repositories\Contracts\AuthorizationRoleRepository as AuthorizationRoleRepositoryContract;
use Symfony\Contracts\Translation\TranslatorInterface;
use Profile;
use Language;

class AuthorizationRoleRepository implements AuthorizationRoleRepositoryContract
{
    /** @var Connection $connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * Get ID authorization tole by slug
     *
     * @param $slug
     * @return int
     */
    public function getIdBySlug($slug)
    {
        $authorizationRole = $this->connection->createQueryBuilder()
            ->select('id_authorization_role')
            ->from(_DB_PREFIX_.'authorization_role')
            ->where('slug = :slug')
            ->setParameter('slug', $slug)
            ->execute()
            ->fetch();
        return (int) $authorizationRole['id_authorization_role'];
    }
}
