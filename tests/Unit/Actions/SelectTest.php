<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Select;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private function helperCreateMock(): MockObject
    {
        /** @var MockObject */
        $mock = $this->createMock(ModernPDO::class);

        return $mock;
    }

    private function helperCreateSelect(MockObject $mock): Select
    {
        /** @var ModernPDO */
        $mpdo = $mock;

        return new Select($mpdo, self::TABLE);
    }

    public function dataProvider(): array
    {
        return [
            [1, 'test1'],
            [2, 'test2'],
            [3, 'test3'],
            [4, 'test4'],
            [5, 'test5'],
            [6, 'test6'],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testSelect(int $id, string $name): void
    {
        // test all

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . '', [])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->all();

        // test basic

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('id', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? AND name=? LIMIT 1', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('id', $id)->and('name', $name)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? OR name=? LIMIT 1', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('id', $id)->or('name', $name)->one();

        // test 'and' or 'or' first

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->and('id', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->or('id', $id)->one();

        // test broken where name

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->and('', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' LIMIT 1', [])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->or('', $id)->one();

        // test (first/last)By

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE name=? ORDER BY id ASC LIMIT 1', [$name])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('name', $name)->firstBy('id');

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE name=? ORDER BY id DESC LIMIT 1', [$name])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->where('name', $name)->lastBy('id');

        // test columns

        /*
        assertArrayHasKey('id', $mpdo->select(self::TABLE)->columns([])->where('id', 1)->one());
        */

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT name FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->columns(['name'])->where('id', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT id, name FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->columns(['id', 'name'])->where('id', $id)->one();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('SELECT * FROM ' . self::TABLE . ' WHERE id=? LIMIT 1', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateSelect($mock)->columns([])->where('id', $id)->one();
    }
}
