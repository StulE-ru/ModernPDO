<?php

namespace ModernPDO\Traits;

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * Trait for working with 'where'.
 */
trait WhereTrait
{
    /** List of where. */
    protected string $where = '1';
    /** Array of placeholders. */
    protected array $where_params = [];

    /**
     * Set first condition.
     */
    public function where(string $name, mixed $value, string $sign = '='): Select|Update|Delete
    {
        if (empty($name)) {
            return $this;
        }

        $this->where = "`{$name}`{$sign}?";
        $this->where_params = [$value];

        return $this;
    }

    /**
     * Adds 'and' condition.
     */
    public function and(string $name, mixed $value, string $sign = '='): Select|Update|Delete
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->where_params)) {
            return $this->where($name, $value, $sign);
        }

        $this->where .= " AND `{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }

    /**
     * Adds 'or' condition.
     */
    public function or(string $name, mixed $value, string $sign = '='): Select|Update|Delete
    {
        if (empty($name)) {
            return $this;
        }

        if (empty($this->where_params)) {
            return $this->where($name, $value, $sign);
        }

        $this->where .= " OR `{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }
}
