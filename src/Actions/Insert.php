<?php

namespace ModernPDO\Actions;

use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use ModernPDO\Traits\ValuesTrait;

/**
 * Class for inserting rows to a table.
 */
class Insert
{
    use ValuesTrait;

    /** The SQL statement. */
    protected string $query;

    public function __construct(
        protected ModernPDO $mpdo,
        protected string $table,
    ) {
    }

    protected function buildQuery(): string
    {
        return '';
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
        return $this->values_params;
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
     * Inserts row into table.
     */
    public function execute(): bool
    {
        $this->query = 'INSERT INTO ' . $this->table . ' (' . $this->columns . ') VALUES (' . $this->values . ')';

        return $this->exec()->rowCount() > 0;
    }
}
