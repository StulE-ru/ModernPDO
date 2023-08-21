<?php

namespace ModernPDO\Tests\Integration;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertTrue;

class CRUDTest extends IntegrationTestCase
{
    protected const TABLE = 'integration_tests_crud';

    public function testInsert(): void
    {
        assertTrue($this->mpdo->insert(self::TABLE)->columns([
            'id', 'name',
        ])->values([
            [1, 'test1'],
            [2, 'test2'],
            [3, 'test3'],
            [4, 'test4'],
            [5, 'test5'],
            [6, 'test6'],
        ])->execute());

        assertTrue($this->mpdo->insert(self::TABLE)->columns(['id', 'name'])->values([[100, 'test100']])->values([[10, 'test10']])->execute());
        assertEquals('test10', $this->mpdo->select(self::TABLE)->where('id', 10)->one()['name']);

        assertTrue($this->mpdo->insert(self::TABLE)->columns(['id', 'name'])->values([[]])->values([[11, 'test11']])->execute());
        assertEquals('test11', $this->mpdo->select(self::TABLE)->where('id', 11)->one()['name']);

        assertTrue($this->mpdo->insert(self::TABLE)->values([[]])->values([[12, 'test12']])->execute());
        assertEquals('test12', $this->mpdo->select(self::TABLE)->where('id', 12)->one()['name']);
    }

    public function testSelect(): void
    {
        $this->testInsert();

        // test basic
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->one()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('name', 'unknown')->one());
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->and('name', 'test1')->one()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('id', 1)->and('name', 'unknown')->one());
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->or('name', 'unknown')->one()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('id', 1000)->or('name', 'unknown')->one());

        // test 'and' or 'or' first
        assertEquals('test1', $this->mpdo->select(self::TABLE)->and('id', 1)->one()['name']);
        assertEquals('test1', $this->mpdo->select(self::TABLE)->or('id', 1)->one()['name']);

        // test broken where name
        assertEquals('test2', $this->mpdo->select(self::TABLE)->where('', 1)->and('id', 2)->one()['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->and('', 1)->and('id', 2)->one()['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->or('', 1)->and('id', 2)->one()['name']);

        // test all
        assertGreaterThan(0, \count($this->mpdo->select(self::TABLE)->all()));

        // test (first/last)By
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->firstBy('id')['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->lastBy('id')['name']);

        // test columns
        assertEquals('test1', $this->mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->one()['name']);
        assertArrayNotHasKey('id', $this->mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->one());
        assertArrayHasKey('id', $this->mpdo->select(self::TABLE)->columns(['id', 'name'])->where('id', 1)->one());
        assertArrayHasKey('id', $this->mpdo->select(self::TABLE)->columns([])->where('id', 1)->one());
    }

    public function testUpdate(): void
    {
        $this->testInsert();

        // update all
        assertTrue($this->mpdo->update(self::TABLE)->set(['name' => 'test'])->execute());

        // test basic
        assertTrue($this->mpdo->update(self::TABLE)->set(['id' => 101])->where('id', 1)->execute());
        assertTrue($this->mpdo->update(self::TABLE)->set(['id' => 102])->and('id', 2)->execute());
        assertTrue($this->mpdo->update(self::TABLE)->set(['id' => 103])->or('id', 3)->execute());
        assertTrue($this->mpdo->update(self::TABLE)->set(['id' => 104, 'name' => 'test104'])->where('id', 4)->execute());

        // check updates
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 101)->one()['name']);
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 102)->one()['name']);
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 103)->one()['name']);
        assertEquals('test104', $this->mpdo->select(self::TABLE)->where('id', 104)->one()['name']);

        // test broken set
        assertFalse($this->mpdo->update(self::TABLE)->execute());
        assertFalse($this->mpdo->update(self::TABLE)->set([])->execute());
    }

    public function testDelete(): void
    {
        $this->testInsert();

        assertTrue($this->mpdo->delete(self::TABLE)->where('id', 1)->execute());
        assertTrue($this->mpdo->delete(self::TABLE)->where('id', 2)->or('name', 'test')->execute());
        assertTrue($this->mpdo->delete(self::TABLE)->where('id', 3)->and('name', 'test3')->execute());

        assertTrue($this->mpdo->select(self::TABLE)->where('id', 1)->one() === []);
        assertTrue($this->mpdo->select(self::TABLE)->where('id', 2)->one() === []);
        assertTrue($this->mpdo->select(self::TABLE)->where('id', 3)->one() === []);
    }
}
