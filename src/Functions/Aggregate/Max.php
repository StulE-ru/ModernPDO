<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Function to get max.
 */
class Max extends AggregateFunction
{
    /**
     * Max function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns max function query.
     */
    public function build(): string
    {
        return 'MAX(' . $this->column . ')';
    }
}
