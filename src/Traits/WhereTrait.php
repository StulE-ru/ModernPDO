<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Trait for working with 'where'.
 */
trait WhereTrait
{
    /**
     * @var array<int, array{type: string, name: string, sign: string, value: mixed}> where conditions
     */
    protected array $where = [];

    /**
     * Returns set query.
     */
    protected function whereQuery(Escaper $escaper): string
    {
        $query = '';

        foreach ($this->where as [
            'type' => $type,
            'name' => $name,
            'sign' => $sign,
            'value' => $value,
        ]) {
            if ($value instanceof ScalarFunction) {
                $value = $value->buildQuery();
            } else {
                $value = '?';
            }

            $query .= $type . ' ' . $escaper->column($name) . $sign . $value . ' ';
        }

        return trim($query);
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function wherePlaceholders(): array
    {
        $placeholders = [];

        foreach ($this->where as [
            'value' => $value,
        ]) {
            if ($value instanceof ScalarFunction) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            } else {
                $placeholders[] = $value;
            }
        }

        return $placeholders;
    }

    /**
     * Adds condition to list.
     */
    protected function addCondition(string $type, string $name, string $sign, mixed $value): void
    {
        $this->where[] = [
            'type' => $type,
            'name' => $name,
            'sign' => $sign,
            'value' => $value,
        ];
    }

    /**
     * Set first condition.
     *
     * @return $this
     */
    public function where(string $name, mixed $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        $this->addCondition('WHERE', $name, $sign, $value);

        return $this;
    }

    /**
     * Adds 'and' condition.
     *
     * @return $this
     */
    public function and(string $name, mixed $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->where)) {
            return $this->where($name, $value, $sign);
        }

        $this->addCondition('AND', $name, $sign, $value);

        return $this;
    }

    /**
     * Adds 'or' condition.
     *
     * @return $this
     */
    public function or(string $name, mixed $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->where)) {
            return $this->where($name, $value, $sign);
        }

        $this->addCondition('OR', $name, $sign, $value);

        return $this;
    }
}
