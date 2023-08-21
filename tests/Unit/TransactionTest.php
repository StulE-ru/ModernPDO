<?php

namespace ModernPDO\Tests\Unit;

use ModernPDO\Transaction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class TransactionTest extends TestCase
{
    private function make(): Transaction
    {
        /** @var MockObject&\PDO */
        $pdo = $this->createMock(\PDO::class);

        $pdo
            ->expects(self::once())
            ->method('beginTransaction')
            ->willReturn(true);

        $pdo
            ->expects(self::once())
            ->method('inTransaction')
            ->willReturn(true);

        $pdo->method('commit')->willReturn(true);
        $pdo->method('rollBack')->willReturn(true);

        return new Transaction($pdo);
    }

    public function testCommit(): void
    {
        $tr = $this->make();

        assertTrue($tr->begin());
        assertTrue($tr->isActive());
        assertTrue($tr->commit());
    }

    public function testRollback(): void
    {
        $tr = $this->make();

        assertTrue($tr->begin());
        assertTrue($tr->isActive());
        assertTrue($tr->rollBack());
    }
}
