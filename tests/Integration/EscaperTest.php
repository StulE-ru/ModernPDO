<?php

namespace ModernPDO\Tests\Integration;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

class EscaperTest extends IntegrationTestCase
{
    protected const TABLE = 'sp\'ecif"ic ta\\bl/e';
    protected const COLUMN = 'sp\'ecif"ic col\\um/n';
    protected const VALUE = 'sp\'ecif"ic va\\lu/e';

    public function testBasic(): void
    {
        $this->mpdo->query('CREATE TABLE IF NOT EXISTS ' . $this->mpdo->escaper()->table(self::TABLE) . ' (id int, ' . $this->mpdo->escaper()->column(self::COLUMN) . ' varchar(32))');

        assertTrue($this->mpdo->insert(self::TABLE)->columns(['id', self::COLUMN])->values([[1, self::VALUE]])->execute());
        assertEquals(self::VALUE, $this->mpdo->select(self::TABLE)->columns(['name' => self::COLUMN])->where('id', 1)->one()['name']);
        assertTrue($this->mpdo->update(self::TABLE)->set([self::COLUMN => 'test'])->where('id', 1)->execute());
        assertEquals('test', $this->mpdo->select(self::TABLE)->columns(['name' => self::COLUMN])->where('id', 1)->one()['name']);
        assertTrue($this->mpdo->delete(self::TABLE)->where(self::COLUMN, 'test')->execute());

        $this->mpdo->query('DROP TABLE IF EXISTS ' . $this->mpdo->escaper()->table(self::TABLE));
    }
}
