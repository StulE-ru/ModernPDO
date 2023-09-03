<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Base class for all fields.
 */
abstract class Field
{
    /**
     * Returns field query.
     */
    abstract public function build(Escaper $escaper): string;
}
