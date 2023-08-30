<?php

namespace ModernPDO\Traits;

use ModernPDO\Conditions\Condition;
use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

/**
 * Trait for working with 'where'.
 */
trait WhereTrait
{
    /**
     * @var list<array{type: string, name: string, sign: string, value: scalar|ScalarFunction|Condition|null}> where conditions
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
            } elseif ($value instanceof Condition) {
                $sign = ' ' . $value->buildSign() . ' ';
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
            } elseif ($value instanceof Condition) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            } else {
                $placeholders[] = $value;
            }
        }

        return $placeholders;
    }

    /**
     * Adds condition to list.
     *
     * @param string                               $type  condition type
     * @param string                               $name  column name
     * @param string                               $sign  condition sign
     * @param scalar|ScalarFunction|Condition|null $value condition value
     */
    protected function addCondition(string $type, string $name, string $sign, string|int|float|bool|null|ScalarFunction|Condition $value): void
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
     * @param string                               $name  column name
     * @param scalar|ScalarFunction|Condition|null $value condition value
     * @param string                               $sign  condition sign
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function where(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
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
     * @param string                               $name  column name
     * @param scalar|ScalarFunction|Condition|null $value condition value
     * @param string                               $sign  condition sign
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function and(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
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
     * @param string                               $name  column name
     * @param scalar|ScalarFunction|Condition|null $value condition value
     * @param string                               $sign  condition sign
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function or(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
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
