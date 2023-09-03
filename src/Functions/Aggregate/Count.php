<?php

namespace ModernPDO\Functions\Aggregate;

use ModernPDO\Escaper;

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
    public function build(Escaper $escaper): string
    {
        return 'COUNT(' . ($this->column !== '' ? $escaper->column($this->column) : '*') . ')';
    }
}
