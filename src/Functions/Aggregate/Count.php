<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Function to get count.
 */
class Count extends AggregateFunction
{
    /**
     * Count function constructor.
     */
    public function __construct(
        private string $column = '',
    ) {
    }

    /**
     * Returns count function query.
     */
    public function build(): string
    {
        return 'COUNT(' . ($this->column !== '' ? $this->column : '*') . ')';
    }
}
