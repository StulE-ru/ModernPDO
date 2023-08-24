<?php

namespace ModernPDO\Functions\Scalar\String;

use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Function to get upper string.
 */
class Upper extends ScalarFunction
{
    /**
     * Upper function constructor.
     */
    public function __construct(
        private string $column,
    ) {
    }

    /**
     * Returns upper function query.
     */
    public function build(): string
    {
        return 'UPPER(' . $this->column . ')';
    }

    /**
     * Returns prepared function query.
     */
    public function buildQuery(): string
    {
        return 'UPPER(?)';
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
