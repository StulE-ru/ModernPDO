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
