<?php

namespace ModernPDO\Traits;

use ModernPDO\Conditions\Condition;
use ModernPDO\Escaper;
use ModernPDO\Functions\Scalar\ScalarFunction;

// TODO: This is duplicate of WhereTrait (only renamed).
//       Move all shared code to shared trait/class.

/**
 * Trait for working with 'on'.
 */
trait OnTrait
{
    /**
     * @var list<array{type: string, name: string, sign: string, value: scalar|null|ScalarFunction|Condition}> on conditions
     */
    protected array $on = [];

    /**
     * Returns set query.
     */
    protected function onQuery(Escaper $escaper): string
    {
        $query = '';

        foreach ($this->on as [
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
            }/* else {
                $value = '?';
            }*/

            $query .= $type . ' ' . $escaper->column($name) . $sign . $escaper->column(strval($value)) . ' ';
        }

        return trim($query);
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function onPlaceholders(): array
    {
        $placeholders = [];

        foreach ($this->on as [
            'value' => $value,
        ]) {
            if ($value instanceof ScalarFunction) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            } elseif ($value instanceof Condition) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            }/* else {
                $placeholders[] = $value;
            }*/
        }

        return $placeholders;
    }

    /**
     * Adds condition to list.
     *
     * @param string $type
     * @param string $name
     * @param string $sign
     * @param scalar|null|ScalarFunction|Condition $value
     */
    protected function addOnCondition(string $type, string $name, string $sign, string|int|float|bool|null|ScalarFunction|Condition $value): void
    {
        $this->on[] = [
            'type' => $type,
            'name' => $name,
            'sign' => $sign,
            'value' => $value,
        ];
    }

    /**
     * Set first condition.
     *
     * @param string $name
     * @param scalar|null|ScalarFunction|Condition $value
     * @param string $sign
     *
     * !!! ATTENTION !!!
     * Method think that $value is name of table like 'join_table.id'
     * and do not prepare it. So be careful with sql-injections.
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function on(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        $this->addOnCondition('ON', $name, $sign, $value);

        return $this;
    }

    /**
     * Adds 'and' condition.
     *
     * @param string $name
     * @param scalar|null|ScalarFunction|Condition $value
     * @param string $sign
     *
     * !!! ATTENTION !!!
     * Method think that $value is name of table like 'join_table.id'
     * and do not prepare it. So be careful with sql-injections.
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function onAnd(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->on)) {
            return $this->on($name, $value, $sign);
        }

        $this->addOnCondition('AND', $name, $sign, $value);

        return $this;
    }

    /**
     * Adds 'or' condition.
     *
     * !!! ATTENTION !!!
     * Method think that $value is name of table like 'join_table.id'
     * and do not prepare it. So be careful with sql-injections.
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function onOr(string $name, string|int|float|bool|null|ScalarFunction|Condition $value, string $sign = '='): object
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->on)) {
            return $this->on($name, $value, $sign);
        }

        $this->addOnCondition('OR', $name, $sign, $value);

        return $this;
    }
}
