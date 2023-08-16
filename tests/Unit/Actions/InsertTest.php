<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Insert;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class InsertTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private function helperCreateMock(): MockObject
    {
        /** @var MockObject */
        $mock = $this->createMock(ModernPDO::class);

        return $mock;
    }

    private function helperCreateInsert(MockObject $mock): Insert
    {
        /** @var ModernPDO */
        $mpdo = $mock;

        return new Insert($mpdo, self::TABLE);
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
    public function testInsert(int $id, string $name): void
    {
        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateInsert($mock)->values(['id' => $id, 'name' => $name])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateInsert($mock)->values(['name' => 'unknown'])->values(['id' => $id, 'name' => $name])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('INSERT INTO ' . self::TABLE . ' (id, name) VALUES (?, ?)', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateInsert($mock)->values([])->values(['id' => $id, 'name' => $name])->execute();
    }
}
