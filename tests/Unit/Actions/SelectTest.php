<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Select;
use ModernPDO\Conditions\Between;
use ModernPDO\Conditions\In;
use ModernPDO\Conditions\NotBetween;
use ModernPDO\Conditions\NotIn;
use ModernPDO\Escaper;
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

    private function make(string $query, array $placeholders, InvokedCount $count = null, Escaper $escaper = null): Select
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
            ->rows();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testBasic(int $id, string $name): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->where('id', $id)->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? AND name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->and('name', $name)->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? OR name=? LIMIT 1', [$id, $name])
            ->where('id', $id)->or('name', $name)->row();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAndOrFirst(int $id, string $name): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->and('id', $id)->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->or('id', $id)->row();
    }

    public function testBrokenName(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->where('', 'test')->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->and('', 'test')->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->or('', 'test')->row();
    }

    public function testOrderBy(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' ORDER BY id ASC LIMIT 1', [])
            ->orderBy('id')->row();

        $this->make('SELECT * FROM ' . self::TABLE . ' ORDER BY id DESC LIMIT 1', [])
            ->orderBy('id', false)->row();
    }

    public function testColumns(): void
    {
        $this->make('SELECT name FROM ' . self::TABLE, [])
            ->columns(['name'])->rows();

        $this->make('SELECT id, name FROM ' . self::TABLE, [])
            ->columns(['id', 'name'])->rows();

        $this->make('SELECT * FROM ' . self::TABLE, [])
            ->columns([])->rows();
    }

    public function testAggregateFunctions(): void
    {
        $this->make('SELECT COUNT(*) FROM ' . self::TABLE, [])
            ->columns([new Count()])->rows();

        $this->make('SELECT SUM(id) FROM ' . self::TABLE, [])
            ->columns([new Sum('id')])->rows();

        $this->make('SELECT MIN(id), MAX(id) FROM ' . self::TABLE, [])
            ->columns([
                new Min('id'),
                new Max('id'),
            ])->rows();
    }

    public function testScalarStringFunctions(): void
    {
        $this->make('SELECT LOWER(name) FROM ' . self::TABLE, [])
            ->columns([new Lower('name')])->rows();

        $this->make('SELECT UPPER(name) FROM ' . self::TABLE, [])
            ->columns([new Upper('name')])->rows();

        $this->make('SELECT LOWER(name), LENGTH(name) FROM ' . self::TABLE, [])
            ->columns([
                new Lower('name'),
                new Lenght('name'),
            ])->rows();
    }

    public function testConditions(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id BETWEEN ? AND ?', [1, 100])
            ->where('id', new Between(1, 100))->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id NOT BETWEEN ? AND ?', [1, 100])
            ->where('id', new NotBetween(1, 100))->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id IN (?, ?, ?)', [1, 2, 3])
            ->where('id', new In([1, 2, 3]))->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id NOT IN (?, ?, ?)', [1, 2, 3])
            ->where('id', new NotIn([1, 2, 3]))->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' WHERE id NOT IN (?, ?, ?) AND id BETWEEN ? AND ?', [1, 2, 3, 3, 6])
            ->where('id', new NotIn([1, 2, 3]))->and('id', new Between(3, 6))->rows();
    }

    public function testJoins(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table', [])
            ->innerJoin('join_table')->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' LEFT OUTER JOIN join_table', [])
            ->leftJoin('join_table')->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' RIGHT OUTER JOIN join_table', [])
            ->rightJoin('join_table')->rows();
    }

    public function testJoinsOn(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id', [])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id AND ' . self::TABLE . '.id=join_table.id', [])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->onAnd(self::TABLE . '.id', 'join_table.id')->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id OR ' . self::TABLE . '.id=join_table.id', [])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->onOr(self::TABLE . '.id', 'join_table.id')->rows();
    }

    public function testJoinsOnWhere(): void
    {
        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id WHERE id=?', [1])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->where('id', 1)->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id WHERE id=? AND name=?', [1, 'name'])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->where('id', 1)->and('name', 'name')->rows();

        $this->make('SELECT * FROM ' . self::TABLE . ' INNER JOIN join_table ON ' . self::TABLE . '.id=join_table.id AND ' . self::TABLE . '.name=join_table.name WHERE id=? AND name=?', [1, 'name'])
            ->innerJoin('join_table')->on(self::TABLE . '.id', 'join_table.id')->onAnd(self::TABLE . '.name', 'join_table.name')->where('id', 1)->and('name', 'name')->rows();
    }

    public function testAsWithColumns(): void
    {
        $this->make('SELECT COUNT(*) AS count FROM ' . self::TABLE, [])
            ->columns(['count' => new Count()])->rows();

        $this->make('SELECT SUM(id) AS sum FROM ' . self::TABLE, [])
            ->columns(['sum' => new Sum('id')])->rows();

        $this->make('SELECT MIN(id) AS min, MAX(id) AS max FROM ' . self::TABLE, [])
            ->columns([
                'min' => new Min('id'),
                'max' => new Max('id'),
            ])->rows();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testEscaper(int $id, string $name): void
    {
        /** @var MockObject&Escaper */
        $escaper = $this->createMock(Escaper::class);

        $escaper
            ->method('table')
            ->willReturn('[table]');

        $escaper
            ->method('column')
            ->willReturn('[column]');

        $this->make('SELECT [column] AS [column] FROM [table] WHERE [column]=?', [$id], escaper: $escaper)
            ->columns(['name' => 'name'])->where('id', $id)->rows();

        $this->make('SELECT COUNT(*) AS [column], MAX([column]) AS [column] FROM [table] WHERE [column]=? ORDER BY [column] ASC LIMIT 1', [$id], escaper: $escaper)
            ->columns([
                'count' => new Count(),
                'max' => new Max('id'),
            ])->where('id', $id)->orderBy('id')->row();

        $this->make('SELECT COUNT(*) AS [column], MAX([column]) AS [column] FROM [table] WHERE [column]=? ORDER BY [column] DESC LIMIT 1', [$id], escaper: $escaper)
            ->columns([
                'count' => new Count(),
                'max' => new Max('id'),
            ])->where('id', $id)->orderBy('id', false)->row();
    }
}
