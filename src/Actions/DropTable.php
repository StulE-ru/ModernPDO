<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\CheckIfExistsTrait;

/**
 * Class for deleting tables.
 */
class DropTable extends Action
{
    use CheckIfExistsTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'DROP TABLE';

        if ($this->checkIfExists) {
            $query .= ' IF EXISTS';
        }

        $query .= ' ' . $escaper->table($this->table);

        return $query;
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return [];
    }

    /**
     * Inserts row into table.
     */
    public function execute(): bool
    {
        $this->query = $this->buildQuery();

        return $this->exec()->status();
    }
}
