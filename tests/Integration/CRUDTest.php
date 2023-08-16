<?php

namespace ModernPDO\Tests\Integration;

use ModernPDO\ModernPDO;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertTrue;

class CRUDTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private static array $mpdos = [];

    protected function setUp(): void
    {
        foreach ($this->dbsProvider() as $db) {
            /** @var ModernPDO */
            $mpdo = $db[0];

            $mpdo->query('CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (id int, name varchar(32));');
            $mpdo->query('DELETE FROM ' . self::TABLE . ';');
        }
    }

    public function dbsProvider(): array
    {
        if (empty(self::$mpdos)) {
            self::$mpdos = [
                [
                    ModernPDO::createMySQL(
                        host: getenv('MYSQL_HOST'),
                        database: getenv('MYSQL_DATABASE'),
                        username: getenv('MYSQL_USERNAME'),
                        password: getenv('MYSQL_PASSWORD'),
                        charset: getenv('MYSQL_CHARSET'),
                    ),
                ],
                [
                    ModernPDO::createMariaDB(
                        host: getenv('MARIADB_HOST'),
                        database: getenv('MARIADB_DATABASE'),
                        username: getenv('MARIADB_USERNAME'),
                        password: getenv('MARIADB_PASSWORD'),
                        charset: getenv('MARIADB_CHARSET'),
                    ),
                ],
                [
                    ModernPDO::createPostgreSQL(
                        host: getenv('POSTGRES_HOST'),
                        database: getenv('POSTGRES_DATABASE'),
                        username: getenv('POSTGRES_USERNAME'),
                        password: getenv('POSTGRES_PASSWORD'),
                    ),
                ],
                [
                    ModernPDO::createSQLite3(':memory:'),
                ]
            ];
        }

        return self::$mpdos;
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testInsert(ModernPDO $mpdo): void
    {
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 1, 'name' => 'test1'])->execute());
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 2, 'name' => 'test2'])->execute());
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 3, 'name' => 'test3'])->execute());
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 4, 'name' => 'test4'])->execute());
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 5, 'name' => 'test5'])->execute());
        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 6, 'name' => 'test6'])->execute());

        assertTrue($mpdo->insert(self::TABLE)->values(['id' => 100, 'name' => 'test100'])->values(['id' => 10, 'name' => 'test10'])->execute());
        assertEquals('test10', $mpdo->select(self::TABLE)->where('id', 10)->one()['name']);

        assertTrue($mpdo->insert(self::TABLE)->values([])->values(['id' => 11, 'name' => 'test11'])->execute());
        assertEquals('test11', $mpdo->select(self::TABLE)->where('id', 11)->one()['name']);
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testSelect(ModernPDO $mpdo): void
    {
        $this->testInsert($mpdo);

        // test basic
        assertEquals('test1', $mpdo->select(self::TABLE)->where('id', 1)->one()['name']);
        assertEmpty($mpdo->select(self::TABLE)->where('name', 'unknown')->one());
        assertEquals('test1', $mpdo->select(self::TABLE)->where('id', 1)->and('name', 'test1')->one()['name']);
        assertEmpty($mpdo->select(self::TABLE)->where('id', 1)->and('name', 'unknown')->one());
        assertEquals('test1', $mpdo->select(self::TABLE)->where('id', 1)->or('name', 'unknown')->one()['name']);
        assertEmpty($mpdo->select(self::TABLE)->where('id', 1000)->or('name', 'unknown')->one());

        // test 'and' or 'or' first
        assertEquals('test1', $mpdo->select(self::TABLE)->and('id', 1)->one()['name']);
        assertEquals('test1', $mpdo->select(self::TABLE)->or('id', 1)->one()['name']);

        // test broken where name
        assertEquals('test2', $mpdo->select(self::TABLE)->where('', 1)->and('id', 2)->one()['name']);
        assertEquals('test2', $mpdo->select(self::TABLE)->and('', 1)->and('id', 2)->one()['name']);
        assertEquals('test2', $mpdo->select(self::TABLE)->or('', 1)->and('id', 2)->one()['name']);

        // test all
        assertGreaterThan(0, count($mpdo->select(self::TABLE)->all()));

        // test (first/last)By
        assertEquals('test1', $mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->firstBy('id')['name']);
        assertEquals('test2', $mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->lastBy('id')['name']);

        // test columns
        assertEquals('test1', $mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->one()['name']);
        assertArrayNotHasKey('id', $mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->one());
        assertArrayHasKey('id', $mpdo->select(self::TABLE)->columns(['id', 'name'])->where('id', 1)->one());
        assertArrayHasKey('id', $mpdo->select(self::TABLE)->columns([])->where('id', 1)->one());
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testUpdate(ModernPDO $mpdo): void
    {
        $this->testInsert($mpdo);

        // update all
        assertTrue($mpdo->update(self::TABLE)->set(['name' => 'test'])->execute());

        // test basic
        assertTrue($mpdo->update(self::TABLE)->set(['id' => 101])->where('id', 1)->execute());
        assertTrue($mpdo->update(self::TABLE)->set(['id' => 102])->and('id', 2)->execute());
        assertTrue($mpdo->update(self::TABLE)->set(['id' => 103])->or('id', 3)->execute());
        assertTrue($mpdo->update(self::TABLE)->set(['id' => 104, 'name' => 'test104'])->where('id', 4)->execute());

        // check updates
        assertEquals('test', $mpdo->select(self::TABLE)->where('id', 101)->one()['name']);
        assertEquals('test', $mpdo->select(self::TABLE)->where('id', 102)->one()['name']);
        assertEquals('test', $mpdo->select(self::TABLE)->where('id', 103)->one()['name']);
        assertEquals('test104', $mpdo->select(self::TABLE)->where('id', 104)->one()['name']);

        // test broken set
        assertFalse($mpdo->update(self::TABLE)->execute());
        assertFalse($mpdo->update(self::TABLE)->set([])->execute());
    }

    /**
     * @dataProvider dbsProvider
     */
    public function testDelete(ModernPDO $mpdo): void
    {
        $this->testInsert($mpdo);

        assertTrue($mpdo->delete(self::TABLE)->where('id', 1)->execute());
        assertTrue($mpdo->delete(self::TABLE)->where('id', 2)->or('name', 'test')->execute());
        assertTrue($mpdo->delete(self::TABLE)->where('id', 3)->and('name', 'test3')->execute());

        assertTrue($mpdo->select(self::TABLE)->where('id', 1)->one() === []);
        assertTrue($mpdo->select(self::TABLE)->where('id', 2)->one() === []);
        assertTrue($mpdo->select(self::TABLE)->where('id', 3)->one() === []);
    }
}
