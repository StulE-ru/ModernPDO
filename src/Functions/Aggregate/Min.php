<?php

namespace ModernPDO\Functions\Aggregate;

use ModernPDO\Escaper;

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
    public function build(Escaper $escaper): string
    {
        return 'MIN(' . $escaper->column($this->column) . ')';
    }
}
