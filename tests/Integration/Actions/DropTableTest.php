<?php

namespace ModernPDO\Tests\Integration\Actions;

use ModernPDO\Fields\IntField;
use ModernPDO\Tests\Integration\IntegrationTestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class DropTableTest extends IntegrationTestCase
{
    protected const TABLE = 'integration_tests_create_table';

    public function testBasic(): void
    {
        assertTrue(
            $this->mpdo->createTable(self::TABLE)
                ->checkIfExists()
                ->fields([
                    new IntField('id'),
                ])->execute()
        );

        assertTrue(
            $this->mpdo->dropTable(self::TABLE)->execute()
        );

        assertFalse(
            $this->mpdo->insert(self::TABLE)->values([[1]])->execute()
        );

        assertFalse(
            $this->mpdo->dropTable(self::TABLE)->execute()
        );

        assertTrue(
            $this->mpdo->dropTable(self::TABLE)->checkIfExists()->execute()
        );
    }
}
