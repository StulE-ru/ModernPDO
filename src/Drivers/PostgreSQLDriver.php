<?php

namespace ModernPDO\Drivers;

use ModernPDO\Drivers\Escapers\PostgreSQLEscaper;
use ModernPDO\Drivers\Factories\PostgreSQLFactory;
use ModernPDO\ModernPDO;

/**
 * PostgreSQL wrapper over ModernPDO.
 */
class PostgreSQLDriver extends ModernPDO
{
    /** Default PostgreSQL database port. */
    public const DEFAULT_PORT = '5432';

    /**
     * Configures ModernPDO to work with PostgreSQL.
     *
     * @param string $host     The hostname on which the database server reside
     * @param string $database The name of the database
     * @param string $username The user name for the DSN string
     * @param string $password The password for the DSN string
     * @param string $port     Connection port, if empty, default is used
     *
     * @see https://www.php.net/manual/ru/ref.pdo-pgsql.php
     */
    public function __construct(
        string $host,
        string $database,
        string $username,
        string $password,
        string $port = self::DEFAULT_PORT,
    ) {
        $pdo = new \PDO(
            'pgsql:host=' . $host . ';dbname=' . $database . ';port=' . $port,
            $username,
            $password,
        );

        parent::__construct($pdo, PostgreSQLEscaper::class, PostgreSQLFactory::class);
    }
}
