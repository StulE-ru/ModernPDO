<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

trait ValuesTrait
{
    /**
     * @var list<list<scalar|ScalarFunction|null>> values for VALUES
     */
    protected array $values = [];

    /**
     * Returns set query.
     */
    protected function valuesQuery(Escaper $escaper): string
    {
        $query = '';

        foreach ($this->values as $values) {
            $query .= '(';

            foreach ($values as $value) {
                if ($value instanceof ScalarFunction) {
                    $value = $value->buildQuery();
                } else {
                    $value = '?';
                }

                $query .= $value . ', ';
            }

            $query = mb_substr($query, 0, -2) . '), ';
        }

        return mb_substr($query, 0, -2);
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function valuesPlaceholders(): array
    {
        $placeholders = [];

        foreach ($this->values as $values) {
            foreach ($values as $value) {
                if ($value instanceof ScalarFunction) {
                    $placeholders = array_merge($placeholders, $value->buildParams());
                } else {
                    $placeholders[] = $value;
                }
            }
        }

        return $placeholders;
    }

    /**
     * Set values for VALUES.
     *
     * @param list<list<scalar|ScalarFunction|null>> $values values for VALUES
     *
     * @return $this
     */
    public function values(array $values): object
    {
        $this->values = $values;

        return $this;
    }
}
