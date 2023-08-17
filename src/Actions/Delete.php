<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\WhereTrait;

/**
 * Class for deleting rows from a table.
 */
class Delete extends Action
{
    use WhereTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return trim('DELETE FROM ' . $this->table . ' ' . $this->where);
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return $this->where_params;
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
