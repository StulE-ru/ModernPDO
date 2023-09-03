<?php

namespace ModernPDO\Keys;

use ModernPDO\Escaper;

/**
 * Base class for all keys.
 */
abstract class Key
{
    /**
     * Returns key query.
     */
    abstract public function build(Escaper $escaper): string;
}
