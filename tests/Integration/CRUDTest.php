<?php

namespace ModernPDO\Tests\Integration;

use ModernPDO\Conditions\Between;
use ModernPDO\Conditions\In;
use ModernPDO\Fields\IntField;
use ModernPDO\Fields\VarcharField;
use ModernPDO\Functions\Aggregate\Count;
use ModernPDO\Functions\Aggregate\Max;
use ModernPDO\Functions\Aggregate\Min;
use ModernPDO\Functions\Aggregate\Sum;
use ModernPDO\Functions\Scalar\String\Lenght;
use ModernPDO\Functions\Scalar\String\Lower;
use ModernPDO\Functions\Scalar\String\Upper;

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
        assertEquals('test10', $this->mpdo->select(self::TABLE)->where('id', 10)->row()['name']);

        assertTrue($this->mpdo->insert(self::TABLE)->columns(['id', 'name'])->values([[]])->values([[11, 'test11']])->execute());
        assertEquals('test11', $this->mpdo->select(self::TABLE)->where('id', 11)->row()['name']);

        assertTrue($this->mpdo->insert(self::TABLE)->values([[]])->values([[12, 'test12']])->execute());
        assertEquals('test12', $this->mpdo->select(self::TABLE)->where('id', 12)->row()['name']);
    }

    public function testDetailedInsert(): void
    {
        // test scalar functions
        assertTrue($this->mpdo->insert(self::TABLE)->values([
            [1, new Lower('Name')],
            [2, new Upper('Name')],
        ])->execute());

        assertEquals([
            ['name' => 'name'],
            ['name' => 'NAME'],
        ], $this->mpdo->select(self::TABLE)->columns(['name'])->rows());
    }

    public function testSelect(): void
    {
        $this->testInsert();

        // test basic
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->row()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('name', 'unknown')->row());
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->and('name', 'test1')->row()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('id', 1)->and('name', 'unknown')->row());
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->or('name', 'unknown')->row()['name']);
        assertEmpty($this->mpdo->select(self::TABLE)->where('id', 1000)->or('name', 'unknown')->row());

        // test 'and' or 'or' first
        assertEquals('test1', $this->mpdo->select(self::TABLE)->and('id', 1)->row()['name']);
        assertEquals('test1', $this->mpdo->select(self::TABLE)->or('id', 1)->row()['name']);

        // test broken where name
        assertEquals('test2', $this->mpdo->select(self::TABLE)->where('', 1)->and('id', 2)->row()['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->and('', 1)->and('id', 2)->row()['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->or('', 1)->and('id', 2)->row()['name']);

        // test all
        assertGreaterThan(0, \count($this->mpdo->select(self::TABLE)->rows()));

        // test (first/last)By
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->orderBy('id')->row()['name']);
        assertEquals('test2', $this->mpdo->select(self::TABLE)->where('id', 1)->or('id', 2)->orderBy('id', false)->row()['name']);

        // test columns
        assertEquals('test1', $this->mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->row()['name']);
        assertArrayNotHasKey('id', $this->mpdo->select(self::TABLE)->columns(['name'])->where('id', 1)->row());
        assertArrayHasKey('id', $this->mpdo->select(self::TABLE)->columns(['id', 'name'])->where('id', 1)->row());
        assertArrayHasKey('id', $this->mpdo->select(self::TABLE)->columns([])->where('id', 1)->row());

        // test aggregate functions with AS operator
        assertEquals(['count' => 2], $this->mpdo->select(self::TABLE)->columns(['count' => new Count()])->where('id', 1)->or('id', 2)->row());
        assertEquals(['sum' => 3], $this->mpdo->select(self::TABLE)->columns(['sum' => new Sum('id')])->where('id', 1)->or('id', 2)->row());
        assertEquals(['min' => 1, 'max' => '2'], $this->mpdo->select(self::TABLE)->columns([
            'min' => new Min('id'),
            'max' => new Max('id'),
        ])->where('id', 1)->or('id', 2)->row());

        // test scalar functions with AS operator
        assertEquals(['lower' => 'test1'], $this->mpdo->select(self::TABLE)->columns(['lower' => new Lower('name')])->where('id', 1)->row());
        assertEquals(['upper' => 'TEST1'], $this->mpdo->select(self::TABLE)->columns(['upper' => new Upper('name')])->where('id', 1)->row());
        assertEquals(['len' => 5], $this->mpdo->select(self::TABLE)->columns(['len' => new Lenght('name')])->where('id', 1)->row());

        // test conditions
        assertEquals([['id' => 1], ['id' => 3]], $this->mpdo->select(self::TABLE)->columns(['id'])->where('id', new In([1, 3]))->rows());
        assertEquals([['id' => 1], ['id' => 2], ['id' => 3]], $this->mpdo->select(self::TABLE)->columns(['id'])->where('id', new Between(1, 3))->rows());

        // test get cell
        assertEquals(9, $this->mpdo->select(self::TABLE)->columns([new Count()])->cell());
        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->cell(1));
    }

    public function testJoins(): void
    {
        $table = 'test_joins';

        // Drop tables

        $this->mpdo->dropTable(self::TABLE)->checkIfExists()->execute();
        $this->mpdo->dropTable($table)->checkIfExists()->execute();

        // Create tables

        $this->mpdo->createTable(self::TABLE)
            ->checkIfExists()
            ->fields([
                new IntField('id'),
                new VarcharField('name', '32'),
            ])->execute();

        $this->mpdo->createTable($table)
            ->checkIfExists()
            ->fields([
                new IntField('id'),
                new VarcharField('name', '32'),
            ])->execute();

        // Insert values

        assertTrue($this->mpdo->insert(self::TABLE)->columns([
            'id', 'name',
        ])->values([
            [1, 'l1'],
            [2, 'l2'],
            [3, 'l3'],
            [4, 'l4'],
        ])->execute());

        assertTrue($this->mpdo->insert($table)->columns([
            'id', 'name',
        ])->values([
            [1, 'l1'],
            [2, 'r2'],
            [5, 'l3'],
            [6, 'r6'],
        ])->execute());

        // Test joins

        assertEquals(2, $this->mpdo->select(self::TABLE)->columns([new Count()])->innerJoin($table)->on(self::TABLE . '.id', $table . '.id')->cell());

        assertEquals(
            [
                ['id' => 1, 'name' => 'l1'],
                ['id' => 2, 'name' => 'l2'],
            ],
            $this->mpdo->select(self::TABLE)
                ->columns([
                    'id' => self::TABLE . '.id',
                    'name' => self::TABLE . '.name',
                ])->innerJoin($table)->on(self::TABLE . '.id', $table . '.id')->rows()
        );

        assertEquals(4, $this->mpdo->select(self::TABLE)->columns([new Count()])->leftJoin($table)->on(self::TABLE . '.id', $table . '.id')->cell());

        assertEquals(
            [
                ['id' => 1, 'name' => 'l1'],
                ['id' => 2, 'name' => 'l2'],
                ['id' => 3, 'name' => 'l3'],
                ['id' => 4, 'name' => 'l4'],
            ],
            $this->mpdo->select(self::TABLE)
                ->columns([
                    'id' => self::TABLE . '.id',
                    'name' => self::TABLE . '.name',
                ])->leftJoin($table)->on(self::TABLE . '.id', $table . '.id')->rows()
        );

        if ($this->isSQLite3()) {
            try {
                $this->mpdo->select(self::TABLE)->columns([new Count()])->rightJoin($table)->on(self::TABLE . '.id', $table . '.id')->cell();

                $this->fail('SQLite3 must throw exception when testing rightJoin');
            } catch (\Exception $ex) {
            }

            try {
                $this->mpdo->select(self::TABLE)
                    ->columns([
                        'id' => self::TABLE . '.id',
                        'name' => self::TABLE . '.name',
                    ])->rightJoin($table)->on(self::TABLE . '.id', $table . '.id')->rows();

                $this->fail('SQLite3 must throw exception when testing rightJoin');
            } catch (\Exception $ex) {
            }
        } else {
            assertEquals(4, $this->mpdo->select(self::TABLE)->columns([new Count()])->rightJoin($table)->on(self::TABLE . '.id', $table . '.id')->cell());

            assertEquals(
                [
                    ['id' => 1, 'name' => 'l1'],
                    ['id' => 2, 'name' => 'l2'],
                    ['id' => null, 'name' => null],
                    ['id' => null, 'name' => null],
                ],
                $this->mpdo->select(self::TABLE)
                    ->columns([
                        'id' => self::TABLE . '.id',
                        'name' => self::TABLE . '.name',
                    ])->rightJoin($table)->on(self::TABLE . '.id', $table . '.id')->rows()
            );
        }
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
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 101)->row()['name']);
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 102)->row()['name']);
        assertEquals('test', $this->mpdo->select(self::TABLE)->where('id', 103)->row()['name']);
        assertEquals('test104', $this->mpdo->select(self::TABLE)->where('id', 104)->row()['name']);

        // test scalar functions
        assertTrue($this->mpdo->update(self::TABLE)->set(['name' => new Upper('Name')])->where('id', 101)->execute());
        assertEquals('NAME', $this->mpdo->select(self::TABLE)->where('id', 101)->row()['name']);

        assertTrue($this->mpdo->update(self::TABLE)->set(['name' => new Lower('Name')])->where('id', 101)->execute());
        assertEquals('name', $this->mpdo->select(self::TABLE)->where('id', 101)->row()['name']);

        assertTrue($this->mpdo->update(self::TABLE)->set(['name' => new Lenght('Name')])->where('id', 101)->execute());
        assertEquals(4, $this->mpdo->select(self::TABLE)->where('id', 101)->row()['name']);

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

        assertTrue($this->mpdo->select(self::TABLE)->where('id', 1)->row() === []);
        assertTrue($this->mpdo->select(self::TABLE)->where('id', 2)->row() === []);
        assertTrue($this->mpdo->select(self::TABLE)->where('id', 3)->row() === []);
    }
}
