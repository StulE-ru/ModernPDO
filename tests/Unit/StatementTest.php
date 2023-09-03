<?php

namespace ModernPDO\Tests\Unit;

use ModernPDO\Statement;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class StatementTest extends TestCase
{
    public function testNull(): void
    {
        $st = new Statement(null);

        assertEquals(0, $st->rowCount());
        assertEquals([], $st->fetch());
        assertEquals([], $st->fetchAll());
    }

    public function testInvalid(): void
    {
        $st = new Statement(new \PDOStatement());

        assertEquals(0, $st->rowCount());
        assertEquals([], $st->fetch());
        assertEquals([], $st->fetchAll());
    }
}
