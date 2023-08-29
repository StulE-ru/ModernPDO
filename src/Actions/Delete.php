<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\LimitTrait;
use ModernPDO\Traits\OrderByTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for deleting rows from a table.
 */
class Delete extends Action
{
    use LimitTrait;
    use OrderByTrait;
    use WhereTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'DELETE FROM ' . $escaper->table($this->table);

        if (!empty($this->where)) {
            $query .= ' ' . $this->whereQuery($escaper);
        }

        if ($this->orderByColumn !== '') {
            $query .= ' ' . $this->orderByQuery($escaper);
        }

        if ($this->limitCount > 0) {
            $query .= ' ' . $this->limitQuery($escaper);
        }

        return $query;
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return array_merge(
            $this->wherePlaceholders(),
            $this->orderByPlaceholders(),
            $this->limitPlaceholders(),
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
