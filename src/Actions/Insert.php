<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\ColumnsTrait;
use ModernPDO\Traits\ValuesTrait;

/**
 * Class for inserting rows to a table.
 */
class Insert extends Action
{
    use ColumnsTrait;
    use ValuesTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $query = 'INSERT INTO ' . $this->table;

        if (!empty($this->columns)) {
            $query .= ' (' . $this->columnsQuery() . ')';
        }

        $query .= ' VALUES ' . $this->valuesQuery();

        return trim($query);
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return array_merge($this->columnsPlaceholders(), $this->valuesPlaceholders());
    }

    /**
     * Inserts row into table.
     */
    public function execute(): bool
    {
        if (empty($this->values)) {
            return false;
        }

        $this->query = $this->buildQuery();

        return $this->exec()->rowCount() > 0;
    }
}
