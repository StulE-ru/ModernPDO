<?php

namespace ModernPDO\Tests\Unit;

use ModernPDO\Transaction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertTrue;

class TransactionTest extends TestCase
{

    private function helperCreateMock(): MockObject
    {
        /** @var MockObject */
        $mock = $this->createMock(\PDO::class);

        return $mock;
    }

    private function helperCreateTransaction(MockObject $mock): Transaction
    {
        /** @var \PDO */
        $pdo = $mock;

        return new Transaction($pdo);
    }

    public function testCommit(): void
    {
        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('beginTransaction')
            ->willReturn(true);

        $mock
            ->expects(self::once())
            ->method('inTransaction')
            ->willReturn(true);

        $mock
            ->expects(self::once())
            ->method('commit')
            ->willReturn(true);

        $tr = $this->helperCreateTransaction($mock);

        assertTrue($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($tr->commit());
    }

    public function testRollback(): void
    {
        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('beginTransaction')
            ->willReturn(true);

        $mock
            ->expects(self::once())
            ->method('inTransaction')
            ->willReturn(true);

        $mock
            ->expects(self::once())
            ->method('rollBack')
            ->willReturn(true);

        $tr = $this->helperCreateTransaction($mock);

        assertTrue($tr->begin());

        assertTrue($tr->isActive());

        assertTrue($tr->rollBack());
    }
}
