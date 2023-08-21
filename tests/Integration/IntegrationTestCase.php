<?php

namespace ModernPDO\Tests\Integration;

use ModernPDO\Drivers\MariaDBDriver;
use ModernPDO\Drivers\MySQLDriver;
use ModernPDO\Drivers\PostgreSQLDriver;
use ModernPDO\Drivers\SQLite3Driver;
use ModernPDO\ModernPDO;
use PHPUnit\Framework\TestCase;

class IntegrationTestCase extends TestCase
{
    protected const TABLE = '';

    protected ModernPDO $mpdo;

    protected function make(string $type): ModernPDO
    {
        switch ($type) {
            case 'PDO':
                $pdo = new \PDO(
                    'pgsql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE'),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD'),
                );

                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_SERIALIZE);

                return new ModernPDO(
                    pdo: $pdo,
                );

            case 'MySQL':
                return new MySQLDriver(
                    host: getenv('DB_HOST'),
                    database: getenv('DB_DATABASE'),
                    username: getenv('DB_USERNAME'),
                    password: getenv('DB_PASSWORD'),
                    charset: getenv('DB_CHARSET')
                );

            case 'MariaDB':
                return new MariaDBDriver(
                    host: getenv('DB_HOST'),
                    database: getenv('DB_DATABASE'),
                    username: getenv('DB_USERNAME'),
                    password: getenv('DB_PASSWORD'),
                    charset: getenv('DB_CHARSET'),
                );

            case 'PostgreSQL':
                return new PostgreSQLDriver(
                    host: getenv('DB_HOST'),
                    database: getenv('DB_DATABASE'),
                    username: getenv('DB_USERNAME'),
                    password: getenv('DB_PASSWORD'),
                );

            case 'SQLite3':
                return new SQLite3Driver(
                    mode: getenv('DB_MODE'),
                );

            default:
                throw new \Exception('You must set DB_TYPE environment');
        }
    }

    protected function setUp(): void
    {
        $this->mpdo = $this->make(
            getenv('DB_TYPE'),
        );

        $this->mpdo->exec('DROP TABLE IF EXISTS ' . static::TABLE . ';');
        $this->mpdo->exec('CREATE TABLE ' . static::TABLE . ' (id int, name varchar(32));');
    }
}
