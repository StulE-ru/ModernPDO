<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\LimitTrait;
use ModernPDO\Traits\OrderByTrait;
use ModernPDO\Traits\SetTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for updating rows from a table.
 */
class Update extends Action
{
    use SetTrait;
    use WhereTrait;
    use OrderByTrait;
    use LimitTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'UPDATE ' . $escaper->table($this->table) . ' SET ' . $this->setQuery($escaper);

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
            $this->setPlaceholders(),
            $this->wherePlaceholders(),
            $this->orderByPlaceholders(),
            $this->limitPlaceholders(),
        );
    }

    /**
     * Updates rows in table.
     */
    public function execute(): bool
    {
        if (empty($this->set)) {
            return false;
        }

        $this->query = $this->buildQuery();

        return $this->exec()->rowCount() > 0;
    }
}
