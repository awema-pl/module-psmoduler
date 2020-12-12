<?php

namespace Psmoduler\Database\Admin;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;

interface RepresentativeInstallerInterface
{

    /**
     * Install
     *
     * @return array|null
     * @throws \Doctrine\DBAL\DBALException
     */
    public function install();

    /**
     * Uninstall
     *
     * @return array|null
     * @throws DBALException
     */
    public function uninstall();
}
