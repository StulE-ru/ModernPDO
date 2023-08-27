<?php

namespace ModernPDO\Drivers\Factories;

use ModernPDO\Actions\AlterTable;
use ModernPDO\Drivers\Actions\SQLite3\AlterTable as SQLite3AlterTable;
use ModernPDO\Factory;

/**
 * Factory for customize SQLite3.
 */
class SQLite3Factory extends Factory
{
    /**
     * Returns AlterTable object.
     */
    public function alterTable(string $table): AlterTable
    {
        return new SQLite3AlterTable($this->mpdo, $table);
    }
}
