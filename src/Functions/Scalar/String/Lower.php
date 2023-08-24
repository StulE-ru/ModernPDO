<?php

namespace ModernPDO\Functions\Scalar\String;

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
    public function build(): string
    {
        return 'LOWER(' . $this->column . ')';
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
