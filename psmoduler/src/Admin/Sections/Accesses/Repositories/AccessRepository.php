<?php

namespace Psmoduler\Admin\Sections\Accesses\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShopBundle\Security\Admin\Employee;
use Psmoduler\Admin\Sections\Accesses\Repositories\Contracts\AccessRepository as AccessRepositoryContract;
use Symfony\Contracts\Translation\TranslatorInterface;
use Profile;
use Language;

class AccessRepository implements AccessRepositoryContract
{
    /** @var Connection $connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    
    /**
     * Exist access
     *
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return bool
     */
    public function exist($idAuthorizationRole, $idProfile){
        $sql = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(_DB_PREFIX_.'access')
            ->where('id_profile = :id_profile')
            ->andWhere('id_authorization_role = :id_authorization_role')
            ->setParameter('id_profile', $idProfile)
            ->setParameter('id_authorization_role', $idAuthorizationRole)
            ->execute();
        return !!$sql->rowCount();
    }

    /**
     * Delete access by name
     *
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return bool
     */
    public function delete($idAuthorizationRole, $idProfile){
        return !!$this->connection->createQueryBuilder()
            ->delete(_DB_PREFIX_.'access')
            ->where('id_profile = :id_profile')
            ->andWhere('id_authorization_role = :id_authorization_role')
            ->setParameter('id_profile', $idProfile)
            ->setParameter('id_authorization_role', $idAuthorizationRole)
            ->execute();
    }

    /**
     * Add access
     *
     * @param $name
     * @param int $idAuthorizationRole
     * @param int $idProfile
     * @return int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function add($idAuthorizationRole, $idProfile)
    {
        return !!$this->connection->createQueryBuilder()
            ->insert(_DB_PREFIX_.'access')
            ->values([
                'id_profile' =>':id_profile',
                'id_authorization_role' =>':id_authorization_role',
            ])
            ->setParameter('id_profile', $idProfile)
            ->setParameter('id_authorization_role', $idAuthorizationRole)
            ->execute();
    }
}
