<?php

namespace ModernPDO\Traits;

/**
 * Trait for working with 'where'.
 */
trait WhereTrait
{
    /** List of where. */
    protected string $where = '';

    /**
     * Array of placeholders.
     *
     * @var mixed[]
     */
    protected array $where_params = [];

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

        $this->where = 'WHERE ' . $name . $sign . '?';
        $this->where_params = [$value];

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

        if (empty($this->where_params)) {
            return $this->where($name, $value, $sign);
        }

        $this->where .= ' AND ' . $name . $sign . '?';
        $this->where_params[] = $value;

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

        if (empty($this->where_params)) {
            return $this->where($name, $value, $sign);
        }

        $this->where .= ' OR ' . $name . $sign . '?';
        $this->where_params[] = $value;

        return $this;
    }
}
