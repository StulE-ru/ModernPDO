<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Insert;
use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\String\Lower;
use ModernPDO\Functions\Scalar\String\Reverse;
use ModernPDO\Functions\Scalar\String\Upper;
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

        /** @var MockObject&Escaper */
        $escaper = $this->createMock(Escaper::class);

        $escaper
            ->method('table')
            ->willReturnArgument(0);

        $escaper
            ->method('column')
            ->willReturnArgument(0);

        return new Insert($mpdo, $escaper, self::TABLE);
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
    public function testBasic(int $id, string $name): void
    {
        $this->make('INSERT INTO ' . self::TABLE . ' VALUES (?, ?)', [$id, $name])
            ->values([
                [$id, $name],
            ])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' VALUES (?, ?)', [$id, $name])
            ->values([
                ['unknown'],
            ])->values([
                [$id, $name],
            ])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' VALUES (?, ?), (?, ?)', [$id, $name, $id, $name])
            ->values([
                [],
            ])->values([
            ])->values([
                [$id, $name],
                [$id, $name],
            ])->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testColumns(int $id, string $name): void
    {
        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->columns([
                'id', 'name',
            ])->values([
                [$id, $name],
            ])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->columns([
                'id', 'name',
            ])->values([
                ['unknown'],
            ])->values([
                [$id, $name],
            ])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?), (?, ?)', [$id, $name, $id, $name])
            ->columns([
                'id', 'name',
            ])->values([
                [],
            ])->values([
            ])->values([
                [$id, $name],
                [$id, $name],
            ])->execute();

        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?), (?, ?)', [$id, $name, $id, $name], self::never())
            ->columns([
                'id', 'name',
            ])->values([
            ])->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testScalarStringFunctions(int $id, string $name): void
    {
        $this->make('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, LOWER(?)), (?, UPPER(?)), (?, REVERSE(?))', [$id, $name, $id, $name, $id, $name])
            ->columns([
                'id', 'name',
            ])->values([
                [$id, new Lower($name)],
                [$id, new Upper($name)],
                [$id, new Reverse($name)],
            ])->execute();
    }
}
