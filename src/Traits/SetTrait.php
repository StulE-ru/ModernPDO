<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Trait for working with 'where'.
 */
trait SetTrait
{
    /**
     * @var array<string, scalar|ScalarFunction|null> values for SET
     */
    protected array $set = [];

    /**
     * Returns set query.
     */
    protected function setQuery(Escaper $escaper): string
    {
        $query = '';

        foreach ($this->set as $column => $value) {
            if ($value instanceof ScalarFunction) {
                $value = $value->buildQuery();
            } elseif (is_bool($value)) {
                $value = $escaper->boolValue($value);
            } else {
                $value = '?';
            }

            $query .= $escaper->column($column) . '=' . $value . ', ';
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
            if ($value instanceof ScalarFunction) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            } elseif (!is_bool($value)) {
                $placeholders[] = $value;
            }
        }

        return $placeholders;
    }

    /**
     * Set values for SET.
     *
     * @param array<string, scalar|ScalarFunction|null> $values array of values for SET
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
