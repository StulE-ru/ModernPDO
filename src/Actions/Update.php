<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\SetTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for updating rows from a table.
 */
class Update extends Action
{
    use SetTrait;
    use WhereTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return trim('UPDATE ' . $this->table . ' SET ' . $this->setQuery() . ' ' . $this->whereQuery());
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return array_merge($this->setPlaceholders(), $this->wherePlaceholders());
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
