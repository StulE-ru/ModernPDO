<?php

namespace ModernPDO\Functions\Scalar;

use ModernPDO\Functions\BaseFunction;

/**
 * Base class for all scalar functions.
 */
abstract class ScalarFunction extends BaseFunction
{
    /**
     * Returns prepared function query.
     */
    abstract public function buildQuery(): string;

    /**
     * Returns parameters for function.
     *
     * @return list<mixed>
     */
    abstract public function buildParams(): array;
}
