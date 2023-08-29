<?php

namespace ModernPDO\Conditions;

/**
 * In condition.
 */
class In extends Condition
{
    /**
     * In constructor.
     *
     * @param list<mixed> $values condition values
     */
    public function __construct(
        private array $values,
    ) {
    }

    /**
     * Returns condition sign.
     */
    public function buildSign(): string
    {
        return 'IN';
    }

    /**
     * Returns prepared condition query.
     */
    public function buildQuery(): string
    {
        if (empty($this->values)) {
            return '';
        }

        return '(' . str_repeat('?, ', \count($this->values) - 1) . '?)';
    }

    /**
     * Returns parameters for condition.
     *
     * @return list<mixed>
     */
    public function buildParams(): array
    {
        return $this->values;
    }
}
