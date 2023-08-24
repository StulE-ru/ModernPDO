<?php

namespace ModernPDO\Traits;

use ModernPDO\Functions\Aggregate\AggregateFunction;

/**
 * Trait for working with 'columns'.
 */
trait ColumnsTrait
{
    /**
     * @var string|AggregateFunction[] column names
     */
    protected array $columns = [];

    /**
     * Returns list of columns.
     */
    protected function columnsQuery(): string
    {
        if (empty($this->columns)) {
            return '*';
        }

        $query = '';

        foreach ($this->columns as $key => $column) {
            if ($column instanceof AggregateFunction) {
                $column = $column->build();
            }

            $query .= $column;

            if (is_string($key)) {
                $query .= ' AS ' . $key;
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
     * @param string|AggregateFunction[] $columns array of column names
     *
     * @return $this
     */
    public function columns(array $columns): object
    {
        $this->columns = $columns;

        return $this;
    }
}
