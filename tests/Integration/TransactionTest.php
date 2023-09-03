<?php

namespace ModernPDO\Tests\Integration;

use function PHPUnit\Framework\assertEmpty;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class TransactionTest extends IntegrationTestCase
{
    public const TABLE = 'integration_tests_transaction';

    public function testCommit(): void
    {
        $tr = $this->mpdo->transaction();

        assertTrue($tr->begin());
        assertFalse($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($this->mpdo->insert(self::TABLE)->values([[1, 'test1']])->execute());

        assertTrue($tr->commit());
        assertFalse($tr->commit());

        assertEquals('test1', $this->mpdo->select(self::TABLE)->where('id', 1)->row()['name']);
    }

    public function testRollback(): void
    {
        $tr = $this->mpdo->transaction();

        assertTrue($tr->begin());
        assertFalse($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($this->mpdo->insert(self::TABLE)->values([[1, 'test1']])->execute());

        assertTrue($tr->rollBack());
        assertFalse($tr->rollBack());

        assertEmpty($this->mpdo->select(self::TABLE)->where('id', 1)->row());
    }
}
