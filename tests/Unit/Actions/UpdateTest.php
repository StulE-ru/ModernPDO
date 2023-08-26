<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\Update;
use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\String\Lower;
use ModernPDO\Functions\Scalar\String\Reverse;
use ModernPDO\Functions\Scalar\String\Upper;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public const TABLE = 'unit_tests_update';

    private function make(string $query, array $placeholders, ?InvokedCount $count = null, ?Escaper $escaper = null): Update
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
    public function testAll(int $id, string $name): void
    {
        $this->make('UPDATE ' . self::TABLE . ' SET id=?', [$id])
            ->set(['id' => $id])->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set(['id' => $id, 'name' => $name])->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testDoubleSet(int $id, string $name): void
    {
        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set(['id' => 'unknown'])->set(['id' => $id, 'name' => $name])->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [$id, $name])
            ->set([])->set(['id' => $id, 'name' => $name])->execute();
    }

    public function testIncorrect(): void
    {
        $this->make('', [], self::never())
            ->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=?', [], self::never())
            ->set([])->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWhere(int $id, string $name): void
    {
        $this->make('UPDATE ' . self::TABLE . ' SET name=? WHERE id=?', [$name, $id])
            ->set(['name' => $name])->where('id', $id)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? AND name=?', [$id, $name, $id, $name])
            ->set(['id' => $id, 'name' => $name])->where('id', $id)->and('name', $name)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET id=?, name=? WHERE id=? OR name=?', [$id, $name, $id, $name])
            ->set(['id' => $id, 'name' => $name])->where('id', $id)->or('name', $name)->execute();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testScalarStringFunctions(int $id, string $name): void
    {
        $this->make('UPDATE ' . self::TABLE . ' SET name=LOWER(?) WHERE id=?', [$name, $id])
            ->set([
                'name' => new Lower($name),
            ])->where('id', $id)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET name=UPPER(?) WHERE id=?', [$name, $id])
            ->set([
                'name' => new Upper($name),
            ])->where('id', $id)->execute();

        $this->make('UPDATE ' . self::TABLE . ' SET name=REVERSE(?) WHERE id=?', [$name, $id])
            ->set([
                'name' => new Reverse($name),
            ])->where('id', $id)->execute();
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

        $this->make('UPDATE [table] SET [column]=? WHERE [column]=?', [$name, $id], escaper: $escaper)
            ->set(['name' => $name])->where('id', $id)->execute();
    }
}
