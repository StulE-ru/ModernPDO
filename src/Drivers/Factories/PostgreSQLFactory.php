<?php

namespace ModernPDO\Drivers\Factories;

use ModernPDO\Actions\AlterTable;
use ModernPDO\Drivers\Actions\PostgreSQL\AlterTable as PostgreSQLAlterTable;
use ModernPDO\Factory;

/**
 * Factory for customize PostgreSQL.
 */
class PostgreSQLFactory extends Factory
{
    /**
     * Returns AlterTable object.
     */
    public function alterTable(string $table): AlterTable
    {
        return new PostgreSQLAlterTable($this->mpdo, $table);
    }
}
