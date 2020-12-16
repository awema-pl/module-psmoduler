<?php

namespace Psmoduler\Admin\Sections\Installations\Services\Database\Contracts;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;

interface AccessInstaller
{

    /**
     * Install
     *
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function install();

    /**
     * Uninstall
     *
     * @return bool
     * @throws DBALException
     */
    public function uninstall();

}
