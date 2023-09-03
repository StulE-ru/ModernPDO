<?php

namespace ModernPDO\Tests\Integration\Actions;

use ModernPDO\Fields\IntField;
use ModernPDO\Fields\TextField;
use ModernPDO\Fields\VarcharField;
use ModernPDO\Tests\Integration\IntegrationTestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class AlterTableTest extends IntegrationTestCase
{
    protected const TABLE = 'integration_tests_alter_table';

    public function testBasic(): void
    {
        $this->mpdo->dropTable(self::TABLE)
            ->checkIfExists()->execute();

        $this->mpdo->dropTable(self::TABLE . '_test')
            ->checkIfExists()->execute();

        assertTrue(
            $this->mpdo->createTable(self::TABLE)
                ->checkIfExists()
                ->fields([
                    new IntField('id'),
                ])->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->rename(self::TABLE . '_test')->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE . '_test')
                ->rename(self::TABLE)->execute()
        );

        // Single param

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->addColumns([
                    new VarcharField('name', 32),
                ])->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->renameColumns(['name' => 'new_name'])->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->dropColumns(['new_name'])->execute()
        );

        // Multi param

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->addColumns([
                    new VarcharField('name', 32),
                    new TextField('email'),
                ])->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->renameColumns([
                    'name' => 'new_name',
                    'email' => 'new_email',
                ])->execute()
        );

        assertTrue(
            $this->mpdo->alterTable(self::TABLE)
                ->dropColumns(['new_name', 'new_email'])->execute()
        );

        // Invalid query

        assertFalse(
            $this->mpdo->alterTable(self::TABLE)
                ->execute()
        );

        // Try insert

        assertTrue(
            $this->mpdo->insert(self::TABLE)
                ->values([[1]])->execute()
        );
    }
}
