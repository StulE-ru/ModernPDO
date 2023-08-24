<?php

namespace ModernPDO\Functions;

/**
 * Base class for all functions.
 */
abstract class BaseFunction
{
    /**
     * Returns function query.
     */
    abstract public function build(): string;
}
