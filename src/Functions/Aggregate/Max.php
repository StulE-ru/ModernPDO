<?php

namespace ModernPDO\Functions\Aggregate;

use ModernPDO\Escaper;

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
    public function build(Escaper $escaper): string
    {
        return 'MAX(' . $escaper->column($this->column) . ')';
    }
}
