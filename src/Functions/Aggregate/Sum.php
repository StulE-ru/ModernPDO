<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Function to get sum.
 */
class Sum extends AggregateFunction
{
    /**
     * Sum function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns sum function query.
     */
    public function build(): string
    {
        return 'SUM(' . $this->column . ')';
    }
}
