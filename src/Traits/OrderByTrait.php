<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;

/**
 * Trait for working with 'order by'.
 */
trait OrderByTrait
{
    /**
     * @var string column name
     */
    protected string $orderByColumn = '';

    /**
     * @var bool order type
     */
    protected bool $orderBySmallerIsFirst = true;

    /**
     * Returns set query.
     */
    protected function orderByQuery(Escaper $escaper): string
    {
        if ($this->orderByColumn === '') {
            return '';
        }

        $query = 'ORDER BY ' . $escaper->column($this->orderByColumn);

        $query .= $this->orderBySmallerIsFirst ? ' ASC' : ' DESC';

        return $query;
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function orderByPlaceholders(): array
    {
        return [];
    }

    /**
     * Set values for order by.
     *
     * @param string $column         column name
     * @param bool   $smallerIsFirst sort type. If true smaller values are returned first
     *
     * @return $this
     */
    public function orderBy(string $column, bool $smallerIsFirst = true): object
    {
        $this->orderByColumn = $column;
        $this->orderBySmallerIsFirst = $smallerIsFirst;

        return $this;
    }
}
