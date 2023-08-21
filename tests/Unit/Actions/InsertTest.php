<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Insert;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    public const TABLE = 'unit_tests_insert';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null): Insert
    {
        /** @var MockObject&ModernPDO */
        $mpdo = $this->createMock(ModernPDO::class);

        $mpdo
            ->expects($count ?? self::once())
            ->method('query')
            ->with($query, $placeholders)
            ->willReturn(new Statement(null));

        return new Insert($mpdo, self::TABLE);
    }

    public function dataProvider(): array
    {
        return [
            [1, 'Gail Kerr'],
            [2, 'Flora Harvey'],
            [3, 'Khalil Allison'],
            [4, 'Chaya Schneider'],
            [5, 'Bibi Blackburn'],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testInsert(int $id, string $name): void
    {
        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->values(['id' => $id, 'name' => $name])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->values(['name' => 'unknown'])->values(['id' => $id, 'name' => $name])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->values([])->values(['id' => $id, 'name' => $name])->execute();
    }
}
