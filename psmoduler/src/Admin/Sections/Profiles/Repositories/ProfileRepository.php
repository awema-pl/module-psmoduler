<?php

namespace Psmoduler\Admin\Sections\Profiles\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShopBundle\Security\Admin\Employee;
use Psmoduler\Admin\Sections\Profiles\Repositories\Contracts\ProfileRepository as ProfileRepositoryContract;
use Symfony\Contracts\Translation\TranslatorInterface;
use Profile;
use Language;

class ProfileRepository implements ProfileRepositoryContract
{
    /** @var Connection $connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Find
     *
     * @param int $id
     * @return array
     */
    public function find($id)
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('*')
            ->from(_DB_PREFIX_ . 'profile')
            ->where('id_profile = :id_profile')
            ->setParameter('id_profile', $id)
            ->execute();
        return $sql->fetch();
    }


    /**
     * Exist
     *
     * @param string $name
     * @return bool
     */
    public function exist($name)
    {
        $sql = $this->connection->createQueryBuilder()
            ->select('id_profile')
            ->from(_DB_PREFIX_ . 'profile_lang')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->execute();
        return !!$sql->rowCount();
    }

    /**
     * Delete by name
     *
     * @param string $name
     * @return bool
     */
    public function deleteByName($name)
    {
        $profileLang = $this->connection->createQueryBuilder()
            ->select('id_profile')
            ->from(_DB_PREFIX_ . 'profile_lang')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->execute()
            ->fetch();
        $profile = new Profile($profileLang['id_profile']);
        return $profile->delete();
    }

    /**
     * Add profile
     *
     * @param array $names
     * @return int
     * @throws \PrestaShopDatabaseException
     * @throws \PrestaShopException
     */
    public function add($names)
    {
        $profile = new Profile();
        $profile->name = $names;
        $profile->add();
        return $profile->id;
    }

    /**
     * Get ID profile by name
     *
     * @param string $name
     * @return int
     */
    public function getIdProfileByName($name){
        $profileLang = $this->connection->createQueryBuilder()
            ->select('id_profile')
            ->from(_DB_PREFIX_.'profile_lang')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->execute()
            ->fetch();
        return (int) $profileLang['id_profile'];
    }
}
