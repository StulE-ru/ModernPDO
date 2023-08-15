<?php

namespace Tests\Integration;

use ModernPDO\ModernPDO;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;

class ModernPDOTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    public function testCreateMySQL(): void
    {
        $mpdo = ModernPDO::createMySQL(
            host: getenv('MYSQL_HOST'),
            database: getenv('MYSQL_DATABASE'),
            username: getenv('MYSQL_USERNAME'),
            password: getenv('MYSQL_PASSWORD'),
            charset: getenv('MYSQL_CHARSET'),
        );

        assertNotEmpty($mpdo);
    }

    public function testCreateMariaDB(): void
    {
        $mpdo = ModernPDO::createMariaDB(
            host: getenv('MARIADB_HOST'),
            database: getenv('MARIADB_DATABASE'),
            username: getenv('MARIADB_USERNAME'),
            password: getenv('MARIADB_PASSWORD'),
            charset: getenv('MARIADB_CHARSET'),
        );

        assertNotEmpty($mpdo);
    }

    public function testCreatePostgreSQL(): void
    {
        $mpdo = ModernPDO::createPostgreSQL(
            host: getenv('POSTGRES_HOST'),
            database: getenv('POSTGRES_DATABASE'),
            username: getenv('POSTGRES_USERNAME'),
            password: getenv('POSTGRES_PASSWORD'),
        );

        assertNotEmpty($mpdo);
    }

    public function testCreateSQLite3(): void
    {
        $mpdo = ModernPDO::createSQLite3();

        assertNotEmpty($mpdo);
    }

    public function testExec(): void
    {
        $mpdo = ModernPDO::createSQLite3();

        assertNotEmpty($mpdo);

        $mpdo->exec('CREATE TABLE ' . self::TABLE . ' (id int, name varchar(32));');
        $mpdo->exec('INSERT INTO ' . self::TABLE . ' VALUES (1, \'test\'), (2, \'test\');');
        assertEquals(1, $mpdo->exec('DELETE FROM ' . self::TABLE . ' WHERE id=1;'));
        assertEquals(1, $mpdo->exec('UPDATE ' . self::TABLE . ' SET name=\'unknown\' WHERE id=2;'));
    }
}
