<?php

namespace ModernPDO\Actions;

use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for deleting rows from a table.
 */
class Delete
{
    use WhereTrait;

    /** The SQL statement. */
    protected string $query;

    public function __construct(
        protected ModernPDO $mpdo,
        protected string $table,
    ) {
    }

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return 'DELETE FROM ' . $this->table . ' ' . $this->where;
    }

    /**
     * Returns the SQL statement.
     */
    protected function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Returns placeholders.
     */
    protected function getPlaceholders(): array
    {
        return $this->where_params;
    }

    /**
     * Executes query and returns statement.
     */
    protected function exec(): Statement
    {
        return $this->mpdo->query(
            $this->getQuery(),
            $this->getPlaceholders(),
        );
    }

    /**
     * Deletes row from table.
     */
    public function execute(): bool
    {
        $this->query = $this->buildQuery();

        return $this->exec()->rowCount() > 0;
    }
}
