<?php

namespace ModernPDO\Drivers;

use ModernPDO\ModernPDO;

/**
 * MySQL wrapper over ModernPDO.
 */
class MySQLDriver extends ModernPDO
{
    /** Default MySQL database port. */
    public const DEFAULT_PORT = '3306';

    /**
     * Configures ModernPDO to work with MySQL.
     *
     * @param string $host     The hostname on which the database server reside
     * @param string $database The name of the database
     * @param string $username The user name for the DSN string
     * @param string $password The password for the DSN string
     * @param string $charset  Connection charset, if empty, default database charset is used
     * @param string $port     Connection port, if empty, default is used
     *
     * @see https://www.php.net/manual/ru/ref.pdo-mysql.php
     */
    public function __construct(
        string $host,
        string $database,
        string $username,
        string $password,
        string $charset = '',
        string $port = self::DEFAULT_PORT,
    ) {
        $pdo = new \PDO(
            'mysql:host=' . $host . ';dbname=' . $database . ';port=' . $port,
            $username,
            $password,
        );

        if (!empty($charset)) {
            $pdo->exec('SET NAMES ' . $charset);
        }

        parent::__construct($pdo);
    }
}
