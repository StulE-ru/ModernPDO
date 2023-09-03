<?php

namespace ModernPDO\Functions\Scalar\String;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Function to get reverse string.
 */
class Reverse extends ScalarFunction
{
    /**
     * Reverse function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns Reverse function query.
     */
    public function build(Escaper $escaper): string
    {
        return 'REVERSE(' . $escaper->column($this->column) . ')';
    }

    /**
     * Returns prepared function query.
     */
    public function buildQuery(): string
    {
        return 'REVERSE(?)';
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
