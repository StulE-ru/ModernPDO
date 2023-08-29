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
     * @var array<int, array{type: string, name: string, sign: string, value: mixed}> on conditions
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
            } else if ($value instanceof Condition) {
                $sign = ' ' . $value->buildSign() . ' ';
                $value = $value->buildQuery();
            }/* else {
                $value = '?';
            }*/

            $query .= $type . ' ' . $escaper->column($name) . $sign . $value . ' ';
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
            } else if ($value instanceof Condition) {
                $placeholders = array_merge($placeholders, $value->buildParams());
            }/* else {
                $placeholders[] = $value;
            }*/
        }

        return $placeholders;
    }

    /**
     * Adds condition to list.
     */
    protected function addOnCondition(string $type, string $name, string $sign, mixed $value): void
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
     * !!! ATTENTION !!!
     * Method think that $value is name of table like 'join_table.id'
     * and do not prepare it. So be careful with sql-injections.
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function on(string $name, mixed $value, string $sign = '='): object
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
     * !!! ATTENTION !!!
     * Method think that $value is name of table like 'join_table.id'
     * and do not prepare it. So be careful with sql-injections.
     *
     * $value can be subclass of Condition (In, Beetween, etc.)
     * If $value is subclass of Condition $sign will be ignored.
     *
     * @return $this
     */
    public function onAnd(string $name, mixed $value, string $sign = '='): object
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
    public function onOr(string $name, mixed $value, string $sign = '='): object
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
