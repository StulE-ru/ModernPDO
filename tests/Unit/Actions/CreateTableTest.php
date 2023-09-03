<?php

namespace ModernPDO\Tests\Unit\Actions;

use ModernPDO\Actions\CreateTable;
use ModernPDO\Escaper;
use ModernPDO\Fields\BoolField;
use ModernPDO\Fields\IntField;
use ModernPDO\Fields\RealField;
use ModernPDO\Fields\TextField;
use ModernPDO\Fields\VarcharField;
use ModernPDO\Keys\ForeignKey;
use ModernPDO\Keys\PrimaryKey;
use ModernPDO\Keys\UniqueKey;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;
use PHPUnit\Framework\TestCase;

class CreateTableTest extends TestCase
{
    public const TABLE = 'unit_tests_create_table';

    private function make(string $query, array $placeholders, InvokedCount $count = null, Escaper $escaper = null): CreateTable
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

        return new CreateTable($mpdo, self::TABLE);
    }

    public function testBasic(): void
    {
        $this->make('CREATE TABLE IF NOT EXISTS ' . self::TABLE . ' (int INT NOT NULL)', [])
            ->checkIfExists()
            ->fields([
                new IntField('int'),
            ])->execute();
    }

    public function testFields(): void
    {
        // Int

        $this->make('CREATE TABLE ' . self::TABLE . ' (int INT NOT NULL)', [])
            ->fields([
                new IntField('int'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (int INT UNSIGNED NULL DEFAULT 100)', [])
            ->fields([
                new IntField('int', unsigned: true, canBeNull: true, default: 100),
            ])->execute();

        // Bool

        $this->make('CREATE TABLE ' . self::TABLE . ' (bool BOOLEAN NOT NULL)', [])
            ->fields([
                new BoolField('bool'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (bool BOOLEAN NULL DEFAULT 1)', [])
            ->fields([
                new BoolField('bool', canBeNull: true, default: true),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (bool BOOLEAN NULL DEFAULT 0)', [])
            ->fields([
                new BoolField('bool', canBeNull: true, default: false),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (bool BOOLEAN NULL DEFAULT 1)', [])
            ->fields([
                new BoolField('bool', canBeNull: true, default: 1),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (bool BOOLEAN NULL DEFAULT 0)', [])
            ->fields([
                new BoolField('bool', canBeNull: true, default: 0),
            ])->execute();

        // Real

        $this->make('CREATE TABLE ' . self::TABLE . ' (real REAL NOT NULL)', [])
            ->fields([
                new RealField('real'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (real REAL NULL DEFAULT 1.2)', [])
            ->fields([
                new RealField('real', canBeNull: true, default: 1.2),
            ])->execute();

        // Text

        $this->make('CREATE TABLE ' . self::TABLE . ' (text TEXT NOT NULL)', [])
            ->fields([
                new TextField('text'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (text TEXT NULL DEFAULT test)', [])
            ->fields([
                new TextField('text', canBeNull: true, default: 'test'),
            ])->execute();

        // Text

        $this->make('CREATE TABLE ' . self::TABLE . ' (varchar VARCHAR(16) NOT NULL)', [])
            ->fields([
                new VarcharField('varchar', 16),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (varchar VARCHAR(32) NULL DEFAULT test)', [])
            ->fields([
                new VarcharField('varchar', 32, canBeNull: true, default: 'test'),
            ])->execute();

        // Invalid fields

        $this->make('CREATE TABLE ' . self::TABLE . ' (varchar VARCHAR(16) NOT NULL)', [], self::never())
            ->fields([
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (varchar VARCHAR(16) NOT NULL)', [], self::never())
            ->execute();

        // Two fields

        $this->make('CREATE TABLE ' . self::TABLE . ' (varchar1 VARCHAR(16) NOT NULL, varchar2 VARCHAR(32) NOT NULL)', [])
            ->fields([
                new VarcharField('varchar1', 16),
                new VarcharField('varchar2', 32),
            ])->execute();
    }

    public function testKeys(): void
    {
        // Primary

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, PRIMARY KEY (id))', [])
            ->fields([
                new IntField('id'),
            ])->keys([
                new PrimaryKey('id'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, CONSTRAINT key PRIMARY KEY (id))', [])
            ->fields([
                new IntField('id'),
            ])->keys([
                new PrimaryKey('id', 'key'),
            ])->execute();

        // Unique

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, UNIQUE (id))', [])
            ->fields([
                new IntField('id'),
            ])->keys([
                new UniqueKey('id'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, CONSTRAINT key UNIQUE (id))', [])
            ->fields([
                new IntField('id'),
            ])->keys([
                new UniqueKey('id', 'key'),
            ])->execute();

        // Foreign

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, foreign_id INT NOT NULL, FOREIGN KEY (foreign_id) REFERENCES foreign_table(id))', [])
            ->fields([
                new IntField('id'),
                new IntField('foreign_id'),
            ])->keys([
                new ForeignKey('foreign_id', 'foreign_table', 'id'),
            ])->execute();

        $this->make('CREATE TABLE ' . self::TABLE . ' (id INT NOT NULL, foreign_id INT NOT NULL, CONSTRAINT key FOREIGN KEY (foreign_id) REFERENCES foreign_table(id))', [])
            ->fields([
                new IntField('id'),
                new IntField('foreign_id'),
            ])->keys([
                new ForeignKey('foreign_id', 'foreign_table', 'id', 'key'),
            ])->execute();
    }
}
