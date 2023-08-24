<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Select;
use ModernPDO\Functions\Aggregate\Count;
use ModernPDO\Functions\Aggregate\Max;
use ModernPDO\Functions\Aggregate\Min;
use ModernPDO\Functions\Aggregate\Sum;
use ModernPDO\Functions\Scalar\String\Lenght;
use ModernPDO\Functions\Scalar\String\Lower;
use ModernPDO\Functions\Scalar\String\Upper;
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

    public function testAll(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE, [])
            ->all();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testBasic(int $id, string $name): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->where('id', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? AND name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->and('name', $name)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? OR name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->or('name', $name)->one();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAndOrFirst(int $id, string $name): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->and('id', $id)->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->or('id', $id)->one();
    }

    public function testBrokenName(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->where('', 'test')->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->and('', 'test')->one();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->or('', 'test')->one();
    }

    public function testOrderBy(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' ORDER BY id ASC LIMIT 1', [])
            ->firstBy('id');

        $this->make('SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT 1', [])
            ->lastBy('id');
    }

    public function testColumns(): void
    {
        $this->make('SELECT name FROM ' . self::TABLE, [])
            ->columns(['name'])->all();

        $this->make('SELECT id, name FROM ' . self::TABLE, [])
            ->columns(['id', 'name'])->all();

        $this->make('SELECT * FROM ' . self::TABLE, [])
            ->columns([])->all();
    }

    public function testAggregateFunctions(): void
    {
        $this->make('SELECT COUNT(*) FROM ' . self::TABLE, [])
            ->columns([new Count()])->all();

        $this->make('SELECT SUM(id) FROM ' . self::TABLE, [])
            ->columns([new Sum('id')])->all();

        $this->make('SELECT MIN(id), MAX(id) FROM ' . self::TABLE, [])
            ->columns([
                new Min('id'),
                new Max('id'),
            ])->all();
    }

    public function testScalarStringFunctions(): void
    {
        $this->make('SELECT LOWER(name) FROM ' . self::TABLE, [])
            ->columns([new Lower('name')])->all();

        $this->make('SELECT UPPER(name) FROM ' . self::TABLE, [])
            ->columns([new Upper('name')])->all();

        $this->make('SELECT LOWER(name), LENGTH(name) FROM ' . self::TABLE, [])
            ->columns([
                new Lower('name'),
                new Lenght('name'),
            ])->all();
    }

    public function testAsWithColumns(): void
    {
        $this->make('SELECT COUNT(*) AS count FROM ' . self::TABLE, [])
            ->columns(['count' => new Count()])->all();

        $this->make('SELECT SUM(id) AS sum FROM ' . self::TABLE, [])
            ->columns(['sum' => new Sum('id')])->all();

        $this->make('SELECT MIN(id) AS min, MAX(id) AS max FROM ' . self::TABLE, [])
            ->columns([
                'min' => new Min('id'),
                'max' => new Max('id'),
            ])->all();
    }
}
