<?php

namespace ModernPDO\Tests\Integration;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;

class ModernPDOTest extends IntegrationTestCase
{
    public const TABLE = 'integration_tests_modernpdo';

    public function testExec(): void
    {
        $this->mpdo->exec('INSERT INTO ' . self::TABLE . ' VALUES (1, \'test\'), (2, \'test\');');
        assertEquals(1, $this->mpdo->exec('DELETE FROM ' . self::TABLE . ' WHERE id=1;'));
        assertEquals(1, $this->mpdo->exec('UPDATE ' . self::TABLE . ' SET name=\'unknown\' WHERE id=2;'));
        assertEquals(0, $this->mpdo->exec('UPDATE ' . self::TABLE . ' SET name=\'unknown\' WHER id=2;')); // Bad query
        assertEquals(0, $this->mpdo->query('UPDATE ' . self::TABLE . ' SET name=\'unknown\' WHER id=2;')->rowCount()); // Bad query
    }
}
