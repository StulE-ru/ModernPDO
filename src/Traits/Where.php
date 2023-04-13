<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

trait Where
{
    protected string $where = '1';
    protected array $where_params = [];

    /**
     * @brief Добавление условия.
     *
     * @param string  $name  - имя столбца
     * @param mixed  $value - значение столбца
     * @param string $sign  - знак сравнения
     */
    public function where(string $name, mixed $value, string $sign = '='): Select|Update|Delete
    {
        if (empty($name)) {
            return $this;
        }

        $this->where_params = [];

        $this->where = "`{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }

    /**
     * @brief Добавление следующего условия через AND.
     *
     * @param string  $name  - имя столбца
     * @param mixed  $value - значение столбца
     * @param string $sign  - знак сравнения
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
     * @brief Добавление следующего условия через OR.
     *
     * @param string  $name  - имя столбца
     * @param mixed  $value - значение столбца
     * @param string $sign  - знак сравнения
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
