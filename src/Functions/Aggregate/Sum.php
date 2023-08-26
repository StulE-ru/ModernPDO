<?php

namespace ModernPDO\Functions\Aggregate;

use ModernPDO\Escaper;

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
    public function build(Escaper $escaper): string
    {
        return 'SUM(' . $escaper->column($this->column) . ')';
    }
}
