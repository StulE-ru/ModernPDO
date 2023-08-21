<?php

namespace ModernPDO\Traits;

/**
 * Trait for working with 'where'.
 */
trait SetTrait
{
    /**
     * @var array<string, mixed> values for SET
     */
    protected array $set = [];

    /**
     * Returns set query.
     */
    protected function setQuery(): string
    {
        $query = '';

        foreach ($this->set as $column => $value) {
            $query .= $column . '=?, ';
        }

        return mb_substr($query, 0, -2);
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function setPlaceholders(): array
    {
        $placeholders = [];

        foreach ($this->set as $value) {
            $placeholders[] = $value;
        }

        return $placeholders;
    }

    /**
     * Set values for SET.
     *
     * @param array<string, mixed> $values array of values for SET
     *
     * @return $this
     */
    public function set(array $values): object
    {
        if (empty($values)) {
            return $this;
        }

        $this->set = $values;

        return $this;
    }
}
