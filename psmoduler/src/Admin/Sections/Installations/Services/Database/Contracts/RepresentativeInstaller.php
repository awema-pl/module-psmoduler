<?php

namespace Psmoduler\Admin\Sections\Installations\Services\Database\Contracts;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;

interface RepresentativeInstaller
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
