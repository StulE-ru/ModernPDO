<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\AlterTable;
use ModernPDO\Escaper;
use ModernPDO\Fields\IntField;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class AlterTableTest extends TestCase
{
    public const TABLE = 'unit_tests_alter_table';

    private function make(string $query, array $placeholders, InvokedCount $count = null, Escaper $escaper = null): AlterTable
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

            $escaper
                ->method('stringValue')
                ->willReturnCallback(function (string $value): string {
                    return $value;
                });

            $escaper
                ->method('boolValue')
                ->willReturnCallback(function (bool $value): string {
                    return $value === true ? '1' : '0';
                });
        }

        $mpdo
            ->method('escaper')
            ->willReturn($escaper);

        return new AlterTable($mpdo, self::TABLE);
    }

    public function testBasic(): void
    {
        $this->make('ALTER TABLE ' . self::TABLE . ' RENAME TO ' . self::TABLE . '_test', [])
            ->rename(self::TABLE . '_test')->execute();

        $this->make('ALTER TABLE ' . self::TABLE . ' ADD COLUMN int INT NOT NULL', [])
            ->addColumns([
                new IntField('int'),
            ])->execute();

        $this->make('ALTER TABLE ' . self::TABLE . ' RENAME COLUMN old TO new', [])
            ->renameColumns([
                'old' => 'new',
            ])->execute();

        $this->make('ALTER TABLE ' . self::TABLE . ' DROP COLUMN name', [])
            ->dropColumns(['name'])->execute();

        $this->make('', [], self::never())
            ->execute();
    }
}
