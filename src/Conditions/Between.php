<?php

namespace ModernPDO\Conditions;

/**
 * Between condition.
 */
class Between extends Condition
{
    /**
     * Between constructor.
     *
     * @template T
     *
     * @param T $min minimum condition value
     * @param T $max maximum condition value
     */
    public function __construct(
        private mixed $min,
        private mixed $max,
    ) {
    }

    /**
     * Returns condition sign.
     */
    public function buildSign(): string
    {
        return 'BETWEEN';
    }

    /**
     * Returns prepared condition query.
     */
    public function buildQuery(): string
    {
        return '? AND ?';
    }

    /**
     * Returns parameters for condition.
     *
     * @return list<mixed>
     */
    public function buildParams(): array
    {
        return [
            $this->min,
            $this->max,
        ];
    }
}
