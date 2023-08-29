<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\ColumnsTrait;
use ModernPDO\Traits\JoinsTrait;
use ModernPDO\Traits\LimitTrait;
use ModernPDO\Traits\OrderByTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for getting rows from a table.
 */
class Select extends Action
{
    use ColumnsTrait;
    use JoinsTrait;
    use LimitTrait;
    use OrderByTrait;
    use WhereTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'SELECT ' . $this->columnsQuery($escaper) . ' FROM ' . $escaper->table($this->table);

        if ($this->joinsTable !== '') {
            $query .= ' ' . $this->joinsQuery($escaper);
        }

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
            $this->columnsPlaceholders(),
            $this->joinsPlaceholders(),
            $this->wherePlaceholders(),
            $this->orderByPlaceholders(),
        );
    }

    /**
     * Returns all or few rows from table.
     *
     * @return list<array<string, mixed>>
     */
    public function rows(): array
    {
        $this->query = $this->buildQuery();

        return $this->exec()->fetchAll();
    }

    /**
     * Returns one row from table.
     *
     * @return array<string, mixed>
     */
    public function row(): array
    {
        $this->limit(1);

        $this->query = $this->buildQuery();

        return $this->exec()->fetch();
    }

    /**
     * Returns one row cell from table.
     */
    public function cell(int $column = 0): mixed
    {
        $this->limit(1);

        $this->query = $this->buildQuery();

        return $this->exec()->fetchColumn($column);
    }
}
