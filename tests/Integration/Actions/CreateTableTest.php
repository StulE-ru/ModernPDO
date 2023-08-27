<?php

namespace ModernPDO\Tests\Integration\Actions;

use ModernPDO\Fields\BoolField;
use ModernPDO\Fields\IntField;
use ModernPDO\Fields\RealField;
use ModernPDO\Fields\TextField;
use ModernPDO\Fields\VarcharField;
use ModernPDO\Keys\ForeignKey;
use ModernPDO\Keys\PrimaryKey;
use ModernPDO\Keys\UniqueKey;
use ModernPDO\Tests\Integration\IntegrationTestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class CreateTableTest extends IntegrationTestCase
{
    protected const TABLE = 'integration_tests_create_table';

    public function testFields(): void
    {
        $table = 'test';

        $this->mpdo->exec('DROP TABLE IF EXISTS ' . $table);

        assertTrue(
            $this->mpdo->createTable($table)
                ->checkIfExists()
                ->fields([
                    new IntField('id'),
                    new VarcharField('name', 32),
                    new TextField('email', canBeNull: true, default: null),
                    new RealField('amount', default: 0.0),
                    new BoolField('is_active', default: true),
                ])->execute()
        );

        // Default values

        assertTrue(
            $this->mpdo->insert($table)
                ->columns(['id', 'name'])
                ->values([
                    [1, 'Test1'],
                ])->execute()
        );

        assertEquals([
            'id' => 1,
            'name' => 'Test1',
            'email' => null,
            'amount' => 0.0,
            'is_active' => true,
        ], $this->mpdo->select('test')->where('id', 1)->one());

        // Custom values

        assertTrue(
            $this->mpdo->insert($table)
                ->values([
                    [2, 'Test2', 'fake@email.lol', 10.5, false],
                ])->execute()
        );

        assertEquals([
            'id' => 2,
            'name' => 'Test2',
            'email' => 'fake@email.lol',
            'amount' => 10.5,
            'is_active' => false,
        ], $this->mpdo->select('test')->where('id', 2)->one());
    }

    public function testKeys(): void
    {
        $foreignTable = 'foreign_test';
        $table = 'test';

        $this->mpdo->exec('DROP TABLE IF EXISTS ' . $foreignTable);
        $this->mpdo->exec('DROP TABLE IF EXISTS ' . $table);

        assertTrue(
            $this->mpdo->createTable($foreignTable)
                ->checkIfExists()
                ->fields([
                    new IntField('id'),
                ])->keys([
                    new PrimaryKey('id'),
                ])->execute()
        );

        assertTrue(
            $this->mpdo->insert($foreignTable)
                ->values([[1]])->execute()
        );

        assertTrue(
            $this->mpdo->createTable($table)
                ->checkIfExists()
                ->fields([
                    new IntField('primary'),
                    new IntField('unique_1'),
                    new IntField('unique_2'),
                    new IntField('foreign'),
                ])->keys([
                    new PrimaryKey('primary'),
                    new UniqueKey('unique_1'),
                    new UniqueKey('unique_2'),
                    new ForeignKey('foreign', $foreignTable, 'id'),
                ])->execute()
        );

        // Try insert values

        assertTrue(
            $this->mpdo->insert($table)
                ->values([
                    [1, 1, 1, 1],
                ])->execute()
        );

        assertFalse(
            $this->mpdo->insert($table)
                ->values([
                    [1, 2, 2, 1],
                ])->execute()
        );

        assertFalse(
            $this->mpdo->insert($table)
                ->values([
                    [2, 1, 2, 1],
                ])->execute()
        );

        assertFalse(
            $this->mpdo->insert($table)
                ->values([
                    [2, 2, 1, 1],
                ])->execute()
        );

        assertTrue(
            $this->mpdo->insert($table)
                ->values([
                    [2, 2, 2, 1],
                ])->execute()
        );
    }
}
