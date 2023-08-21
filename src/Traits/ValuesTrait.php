<?php

namespace ModernPDO\Traits;

trait ValuesTrait
{
    /**
     * @var list<list<mixed>> $values values for VALUES
     */
    protected array $values = [];

    /**
     * Returns set query.
     */
    protected function valuesQuery(): string
    {
        $query = '';

        foreach ($this->values as $values) {
            $query .= '(';

            foreach ($values as $value) {
                $query .= '?, ';
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
                $placeholders[] = $value;
            }
        }

        return $placeholders;
    }

    /**
     * Set values for VALUES.
     *
     * @param list<list<mixed>> $values values for VALUES
     *
     * @return $this
     */
    public function values(array $values): object
    {
        $this->values = $values;

        return $this;
    }
}
