<?php

namespace ModernPDO\Traits;

/**
 * Trait for working with 'columns'.
 */
trait ColumnsTrait
{
    /**
     * @var list<string> column names
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

        foreach ($this->columns as $column) {
            $query .= $column . ', ';
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
     * @param list<string> $columns array of column names
     *
     * @return $this
     */
    public function columns(array $columns): object
    {
        $this->columns = $columns;

        return $this;
    }
}
