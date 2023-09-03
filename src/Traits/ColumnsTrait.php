<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;
use ModernPDO\Functions\BaseFunction;

/**
 * Trait for working with 'columns'.
 */
trait ColumnsTrait
{
    /**
     * @var array<int|string, string|BaseFunction> column names
     */
    protected array $columns = [];

    /**
     * Returns list of columns.
     */
    protected function columnsQuery(Escaper $escaper): string
    {
        if (empty($this->columns)) {
            return '*';
        }

        $query = '';

        foreach ($this->columns as $key => $column) {
            if ($column instanceof BaseFunction) {
                $query .= $column->build($escaper);
            } else {
                $query .= $escaper->column($column);
            }

            if (\is_string($key)) {
                $query .= ' AS ' . $escaper->column($key);
            }

            $query .= ', ';
        }

        return mb_substr($query, 0, -2);
    }

    /**
     * Returns empty array.
     *
     * @return list<mixed>
     */
    protected function columnsPlaceholders(): array
    {
        return [];
    }

    /**
     * Set columns.
     *
     * @param array<int|string, string|BaseFunction> $columns array of column names
     *
     * @return $this
     */
    public function columns(array $columns): object
    {
        $this->columns = $columns;

        return $this;
    }
}
