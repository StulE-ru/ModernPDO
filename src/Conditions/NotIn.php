<?php

namespace ModernPDO\Conditions;

/**
 * NotIn condition.
 */
class NotIn extends In
{
    /**
     * Returns condition sign.
     */
    public function buildSign(): string
    {
        return 'NOT IN';
    }
}
