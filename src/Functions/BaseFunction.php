<?php

namespace ModernPDO\Functions;

use ModernPDO\Escaper;

/**
 * Base class for all functions.
 */
abstract class BaseFunction
{
    /**
     * Returns function query.
     */
    abstract public function build(Escaper $escaper): string;
}
