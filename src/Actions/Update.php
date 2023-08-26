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
        $escaper = $this->mpdo->escaper();

        return trim('UPDATE ' . $escaper->table($this->table) . ' SET ' . $this->setQuery($escaper) . ' ' . $this->whereQuery($escaper));
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
