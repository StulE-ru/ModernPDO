<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Update;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private function helperCreateMock(): MockObject
    {
        /** @var MockObject */
        $mock = $this->createMock(ModernPDO::class);

        return $mock;
    }

    private function helperCreateUpdate(MockObject $mock): Update
    {
        /** @var ModernPDO */
        $mpdo = $mock;

        return new Update($mpdo, self::TABLE);
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
    public function testUpdate(int $id, string $name): void
    {
        // update all

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['id' => $id])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['id' => $id, 'name' => $name])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['id' => 'unknown'])->set(['id' => $id, 'name' => $name])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set([])->set(['id' => $id, 'name' => $name])->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::never())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=?', [])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::never())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=?', [])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set([])->execute();

        // update where

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET name=? WHERE id=?', [$name, $id])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['name' => $name])->where('id', $id)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? AND name=?', [$id, $name, $id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['id' => $id, 'name' => $name])->where('id', $id)->and('name', $name)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? OR name=?', [$id, $name, $id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateUpdate($mock)->set(['id' => $id, 'name' => $name])->where('id', $id)->or('name', $name)->execute();
    }
}
