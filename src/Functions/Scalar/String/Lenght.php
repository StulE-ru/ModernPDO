<?php

namespace ModernPDO\Functions\Scalar\String;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Function to get lenght of string.
 */
class Lenght extends ScalarFunction
{
    /**
     * Lenght function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns lenght function query.
     */
    public function build(Escaper $escaper): string
    {
        return 'LENGTH(' . $escaper->column($this->column) . ')';
    }

    /**
     * Returns prepared function query.
     */
    public function buildQuery(): string
    {
        return 'LENGTH(?)';
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
