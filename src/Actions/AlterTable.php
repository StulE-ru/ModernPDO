<?php

namespace ModernPDO\Actions;

/**
 * Class for updating tables.
 */
class AlterTable extends Action
{
    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return '';
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
