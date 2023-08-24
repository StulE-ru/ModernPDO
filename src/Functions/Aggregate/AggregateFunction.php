<?php

namespace ModernPDO\Functions\Aggregate;

/**
 * Base class for all aggregate functions.
 */
abstract class AggregateFunction
{
    /**
     * Returns function query.
     */
    abstract public function build(): string;
}
