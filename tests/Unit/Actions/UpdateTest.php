<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Update;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public const TABLE = 'unit_tests_update';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null): Update
    {
        /** @var MockObject&ModernPDO */
        $mpdo = $this->createMock(ModernPDO::class);

        $mpdo
            ->expects($count ?? self::once())
            ->method('query')
            ->with($query, $placeholders)
            ->willReturn(new Statement(null));

        return new Update($mpdo, self::TABLE);
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
    public function testUpdate(int $id, string $name): void
    {
        // update all

        $this->make('UPDATE ' . self::TABLE . ' SET id=?', [$id])
            ->set(['id' => $id])->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set(['id' => $id, 'name' => $name])->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set(['id' => 'unknown'])->set(['id' => $id, 'name' => $name])->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set([])->set(['id' => $id, 'name' => $name])->execute();

        $this->make('', [], self::never())
            ->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [], self::never())
            ->set([])->execute();

        // update where

        $this->make('UPDATE ' . self::TABLE . ' SET name=? WHERE id=?', [$name, $id])
            ->set(['name' => $name])->where('id', $id)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? AND name=?', [$id, $name, $id, $name])
            ->set(['id' => $id, 'name' => $name])->where('id', $id)->and('name', $name)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? OR name=?', [$id, $name, $id, $name])
            ->set(['id' => $id, 'name' => $name])->where('id', $id)->or('name', $name)->execute();
    }
}
