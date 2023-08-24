<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Function to get min.
 */
class Min extends AggregateFunction
{
    /**
     * Min function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns min function query.
     */
    public function build(): string
    {
        return 'MIN(' . $this->column . ')';
    }
}
