<?php

namespace Psmoduler\Database\Admin;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;

class RepresentativeInstaller implements RepresentativeInstallerInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var string
     */
    private $dbPrefix;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
        $this->dbPrefix = _DB_PREFIX_;
    }

    /**
     * Install
     *
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function install()
    {
        return true;
        $error = null;
        $this->uninstall();
        $sqlInstallFile = _PS_MODULE_DIR_ .'psmoduler/resources/data/admin/install-representative.sql';
        $sqlQuery=file_get_contents($sqlInstallFile);
        $sqlQuery = str_replace('PREFIX_', $this->dbPrefix, $sqlQuery);

        if (!empty($sqlQuery)) {
            $statement = $this->connection->executeQuery($sqlQuery);
            if (0 != (int) $statement->errorCode()) {
                $error = [
                    'key' => json_encode($statement->errorInfo()),
                    'parameters' => [],
                    'domain' => 'Modules.Psmoduler.Psmoduler',
                ];
            }
        }

        return $error;
    }

    /**
     * Uninstall
     *
     * @return array|null
     * @throws DBALException
     */
    public function uninstall()
    {
        $error = null;
        $sqlUninstallFile = _PS_MODULE_DIR_ .'psmoduler/resources/data/admin/uninstall-representative.sql';
        $sqlQuery=file_get_contents($sqlUninstallFile);
        $sqlQuery = str_replace('PREFIX_', $this->dbPrefix, $sqlQuery);

        if (!empty($sqlQuery)) {
            $statement = $this->connection->executeQuery($sqlQuery);
            if (0 != (int) $statement->errorCode()) {
                $error = [
                    'key' => json_encode($statement->errorInfo()),
                    'parameters' => [],
                    'domain' => 'Modules.Psmoduler.Psmoduler',
                ];
            }
        }

        return $error;
    }
}
