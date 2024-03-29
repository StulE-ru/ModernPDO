<?php

namespace ModernPDO\Drivers;

use ModernPDO\Drivers\Escapers\SQLite3Escaper;
use ModernPDO\Drivers\Factories\SQLite3Factory;
use ModernPDO\ModernPDO;

/**
 * SQLite3 wrapper over ModernPDO.
 */
class SQLite3Driver extends ModernPDO
{
    /**
     * Configures ModernPDO to work with SQLite3.
     *
     * @param string $mode Full path to the database file or :memory: or an empty string
     *
     * - To access a database on disk, $mode must be full path to the database file.
     * - To create a database in memory, $mode must be ':memory:'.
     * - To use a temporary database, which is deleted when the connection is closed, $mode must be an empty string.
     *
     * @see https://www.php.net/manual/en/ref.pdo-sqlite.connection.php
     */
    public function __construct(
        string $mode = '',
    ) {
        $pdo = new \PDO('sqlite:' . $mode);

        parent::__construct($pdo, SQLite3Escaper::class, SQLite3Factory::class);
    }
}
