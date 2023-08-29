<?php

namespace ModernPDO\Conditions;

/**
 * Base class for all conditions.
 *
 * Conditions are
 */
abstract class Condition
{
    /**
     * Returns condition sign.
     */
    abstract public function buildSign(): string;

    /**
     * Returns prepared condition query.
     */
    abstract public function buildQuery(): string;

    /**
     * Returns parameters for condition.
     *
     * @return list<mixed>
     */
    abstract public function buildParams(): array;
}
