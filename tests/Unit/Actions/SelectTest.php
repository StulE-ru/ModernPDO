<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Select;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    public const TABLE = 'unit_tests_select';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null): Select
    {
        /** @var MockObject&ModernPDO */
        $mpdo = $this->createMock(ModernPDO::class);

        $mpdo
            ->expects($count ?? self::once())
            ->method('query')
            ->with($query, $placeholders)
            ->willReturn(new Statement(null));

        return new Select($mpdo, self::TABLE);
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
    public function testSelect(int $id, string $name): void
    {
        // test all

        $this->make('SELECT * FROM ' . self::TABLE, [])
            ->all();

        // test basic

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->where('id', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? AND name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->and('name', $name)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? OR name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->or('name', $name)->one();

        // test 'and' or 'or' first

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->and('id', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->or('id', $id)->one();

        // test broken where name

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->where('', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->and('', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->or('', $id)->one();

        // test (first/last)By

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE name=? ORDER BY id ASC LIMIT 1', [$name])
            ->where('name', $name)->firstBy('id');

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE name=? ORDER BY id DESC LIMIT 1', [$name])
            ->where('name', $name)->lastBy('id');

        // test columns

        $this->make('SELECT name FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->columns(['name'])->where('id', $id)->one();

        $this->make('SELECT id, name FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->columns(['id', 'name'])->where('id', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->columns([])->where('id', $id)->one();
    }
}
