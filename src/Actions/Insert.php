<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\ValuesTrait;

/**
 * Class for inserting rows to a table.
 */
class Insert extends Action
{
    use ValuesTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return 'INSERT INTO ' . $this->table . ' (' . $this->columns . ') VALUES (' . $this->values . ')';
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return $this->values_params;
    }

    /**
     * Inserts row into table.
     */
    public function execute(): bool
    {
        $this->query = $this->buildQuery();

        return $this->exec()->rowCount() > 0;
    }
}
