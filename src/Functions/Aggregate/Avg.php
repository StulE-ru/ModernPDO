<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Function to get avg.
 */
class Avg extends AggregateFunction
{
    /**
     * Avg function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns avg function query.
     */
    public function build(): string
    {
        return 'AVG(' . $this->column . ')';
    }
}
