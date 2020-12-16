<?php

namespace Psmoduler\Admin\Sections\Installations\Services\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Psmoduler\Admin\Sections\Commons\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Installations\Services\Database\Contracts\RepresentativeInstaller as RepresentativeInstallerContract;
use Context;
use Module;

class RepresentativeInstaller implements RepresentativeInstallerContract
{
    /** @var Module $module */
    private $module;

    public function __construct(Module $module){
        $this->module = $module;
    }

    /**
     * Install
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function install()
    {
        $connection = $this->module->get('doctrine.dbal.default_connection');
        $this->uninstall();
        $sqlInstallFile = _PS_MODULE_DIR_ .'psmoduler/database/sql/2020_12_11_230000_create_representatives_table.sql';
        $sqlQuery=file_get_contents($sqlInstallFile);
        $sqlQuery = str_replace('PREFIX_', _DB_PREFIX_, $sqlQuery);
        if (!empty($sqlQuery)) {
            $statement = $connection->executeQuery($sqlQuery);
            if (0 != (int) $statement->errorCode()) {
                throw new PsmodulerException('Error install ' .$sqlInstallFile. ' ' . json_encode($statement->errorInfo()));
            }
        }
        return true;
    }

    /**
     * Uninstall
     *
     * @return bool
     * @throws DBALException
     */
    public function uninstall()
    {
        $connection = $this->module->get('doctrine.dbal.default_connection');
        $sqlUninstallFile = _PS_MODULE_DIR_ .'psmoduler/database/sql/2020_12_11_230000_drop_representatives_table.sql';
        $sqlQuery=file_get_contents($sqlUninstallFile);
        $sqlQuery = str_replace('PREFIX_', _DB_PREFIX_, $sqlQuery);
        if (!empty($sqlQuery)) {
            $statement = $connection->executeQuery($sqlQuery);
            if (0 != (int) $statement->errorCode()) {
                throw new PsmodulerException('Error install ' .$sqlUninstallFile. ' ' . json_encode($statement->errorInfo()));
            }
        }
        return true;
    }
}
