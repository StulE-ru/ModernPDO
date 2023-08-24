<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Delete;
use ModernPDO\Functions\Scalar\String\Lower;
use ModernPDO\Functions\Scalar\String\Reverse;
use ModernPDO\Functions\Scalar\String\Upper;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    public const TABLE = 'unit_tests_delete';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null): Delete
    {
        /** @var MockObject&ModernPDO */
        $mpdo = $this->createMock(ModernPDO::class);

        $mpdo
            ->expects($count ?? self::once())
            ->method('query')
            ->with($query, $placeholders)
            ->willReturn(new Statement(null));

        return new Delete($mpdo, self::TABLE);
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
        $this->make('DELETE FROM ' . self::TABLE, [])
            ->execute();

        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=?', [$id])
            ->where('id', $id)->execute();

        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=? OR name=?', [$id, $name])
            ->where('id', $id)->or('name', $name)->execute();

        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=? AND name=?', [$id, $name])
            ->where('id', $id)->and('name', $name)->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testScalarStringFunctions(int $id, string $name): void
    {
        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=? AND name=LOWER(?)', [$id, $name])
            ->where('id', $id)->and('name', new Lower($name))->execute();

        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=? AND name=UPPER(?)', [$id, $name])
            ->where('id', $id)->and('name', new Upper($name))->execute();

        $this->make('DELETE FROM ' . self::TABLE . ' WHERE id=? AND name=REVERSE(?)', [$id, $name])
            ->where('id', $id)->and('name', new Reverse($name))->execute();
    }
}
