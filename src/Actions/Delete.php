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
        $escaper = $this->mpdo->escaper();

        return trim('DELETE FROM ' . $escaper->table($this->table) . ' ' . $this->whereQuery($escaper));
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return $this->wherePlaceholders();
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
