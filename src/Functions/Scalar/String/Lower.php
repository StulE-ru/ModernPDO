<?php

namespace ModernPDO\Functions\Scalar\String;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Function to get lower string.
 */
class Lower extends ScalarFunction
{
    /**
     * Lower function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns lower function query.
     */
    public function build(Escaper $escaper): string
    {
        return 'LOWER(' . $escaper->column($this->column) . ')';
    }

    /**
     * Returns prepared function query.
     */
    public function buildQuery(): string
    {
        return 'LOWER(?)';
    }

    /**
     * Returns parameters for function.
     *
     * @return list<mixed>
     */
    public function buildParams(): array
    {
        return [
            $this->column,
        ];
    }
}
