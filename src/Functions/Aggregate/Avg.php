<?php

namespace ModernPDO\Functions\Aggregate;

use ModernPDO\Escaper;

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
    public function build(Escaper $escaper): string
    {
        return 'AVG(' . $escaper->column($this->column) . ')';
    }
}
