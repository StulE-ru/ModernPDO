<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Delete;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    public const TABLE = 'table_for_tests';

    private function helperCreateMock(): MockObject
    {
        /** @var MockObject */
        $mock = $this->createMock(ModernPDO::class);

        return $mock;
    }

    private function helperCreateDelete(MockObject $mock): Delete
    {
        /** @var ModernPDO */
        $mpdo = $mock;

        return new Delete($mpdo, self::TABLE);
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
    public function testDelete(int $id, string $name): void
    {
        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('DELETE FROM ' . self::TABLE . '', [])
            ->willReturn(new Statement(null));

        $this->helperCreateDelete($mock)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('DELETE FROM ' . self::TABLE . ' WHERE id=?', [$id])
            ->willReturn(new Statement(null));

        $this->helperCreateDelete($mock)->where('id', $id)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('DELETE FROM ' . self::TABLE . ' WHERE id=? OR name=?', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateDelete($mock)->where('id', $id)->or('name', $name)->execute();

        $mock = $this->helperCreateMock();

        $mock
            ->expects(self::once())
            ->method('query')
            ->with('DELETE FROM ' . self::TABLE . ' WHERE id=? AND name=?', [$id, $name])
            ->willReturn(new Statement(null));

        $this->helperCreateDelete($mock)->where('id', $id)->and('name', $name)->execute();
    }
}
