<?php

namespace ModernPDO\Tests\Integration;

use ModernPDO\Drivers\MariaDBDriver;
use ModernPDO\Drivers\MySQLDriver;
use ModernPDO\Drivers\PostgreSQLDriver;
use ModernPDO\Drivers\SQLite3Driver;
use ModernPDO\ModernPDO;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class TransactionTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private static array $mpdos = [];

    protected function setUp(): void
    {
        foreach ($this->dbsProvider() as $db) {
            /** @var ModernPDO */
            $mpdo = $db[0];

            $mpdo->query('CREATE TABLE IF NOT EXISTS ' . self::TABLE . '(id int, name varchar(32));');
            $mpdo->query('DELETE FROM ' . self::TABLE . ';');
        }
    }

    public function dbsProvider(): array
    {
        if (empty(self::$mpdos)) {
            $pdo = new \PDO(
                'pgsql:host=' . getenv('POSTGRES_HOST') . ';dbname=' . getenv('POSTGRES_DATABASE'),
                getenv('POSTGRES_USERNAME'),
                getenv('POSTGRES_PASSWORD'),
            );

            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_SERIALIZE);

            self::$mpdos = [
                [
                    new MySQLDriver(
                        host: getenv('MYSQL_HOST'),
                        database: getenv('MYSQL_DATABASE'),
                        username: getenv('MYSQL_USERNAME'),
                        password: getenv('MYSQL_PASSWORD'),
                        charset: getenv('MYSQL_CHARSET'),
                    ),
                ],
                [
                    new MariaDBDriver(
                        host: getenv('MARIADB_HOST'),
                        database: getenv('MARIADB_DATABASE'),
                        username: getenv('MARIADB_USERNAME'),
                        password: getenv('MARIADB_PASSWORD'),
                        charset: getenv('MARIADB_CHARSET'),
                    ),
                ],
                [
                    new PostgreSQLDriver(
                        host: getenv('POSTGRES_HOST'),
                        database: getenv('POSTGRES_DATABASE'),
                        username: getenv('POSTGRES_USERNAME'),
                        password: getenv('POSTGRES_PASSWORD'),
                    ),
                ],
                [
                    new SQLite3Driver(':memory:'),
                ],
                [
                    new ModernPDO($pdo),
                ],
            ];
        }

        return self::$mpdos;
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testCommit(ModernPDO $mpdo): void
    {
        $tr = $mpdo->transaction();

        assertTrue($tr->begin());
        assertFalse($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 1, 'name' => 'test1'])->execute());

        assertTrue($tr->commit());
        assertFalse($tr->commit());

        assertEquals('test1', $mpdo->select(self::TABLE)->where('id', 1)->one()['name']);
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testRollback(ModernPDO $mpdo): void
    {
        $tr = $mpdo->transaction();

        assertTrue($tr->begin());
        assertFalse($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 1, 'name' => 'test1'])->execute());

        assertTrue($tr->rollBack());
        assertFalse($tr->rollBack());

        assertEmpty($mpdo->select(self::TABLE)->where('id', 1)->one());
    }
}