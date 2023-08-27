<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\DropTable;
use ModernPDO\Escaper;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class DropTableTest extends TestCase
{
    public const TABLE = 'unit_tests_drop_table';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null, ?Escaper $escaper = null): DropTable
    {
        /** @var MockObject&ModernPDO */
        $mpdo = $this->createMock(ModernPDO::class);

        $mpdo
            ->expects($count ?? self::once())
            ->method('query')
            ->with($query, $placeholders)
            ->willReturn(new Statement(null));

        if ($escaper === null) {
            /** @var MockObject&Escaper */
            $escaper = $this->createMock(Escaper::class);

            $escaper
                ->method('table')
                ->willReturnArgument(0);

            $escaper
                ->method('column')
                ->willReturnArgument(0);
        }

        $mpdo
            ->method('escaper')
            ->willReturn($escaper);

        return new DropTable($mpdo, self::TABLE);
    }

    public function testBasic(): void
    {
        $this->make('DROP TABLE ' . self::TABLE, [])
            ->execute();

        $this->make('DROP TABLE IF EXISTS ' . self::TABLE, [])
            ->checkIfExists()
            ->execute();
    }
}
