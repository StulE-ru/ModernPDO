<?php

namespace ModernPDO\Conditions;

/**
 * NotBetween condition.
 */
class NotBetween extends Between
{
    /**
     * Returns condition sign.
     */
    public function buildSign(): string
    {
        return 'NOT BETWEEN';
    }
}
