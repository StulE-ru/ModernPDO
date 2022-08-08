<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;
use ModernPDO\Actions\Delete;

trait Where
{
    protected string $where = "1";
    protected array $where_params = [];

    public function where(string $name, mixed $value, string $sign = "="): Select|Update|Delete
    {
        if ( empty($name) )
            return $this;

        $this->where_params = [];

        $this->where = "`{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }

    public function and(string $name, mixed $value, string $sign = "="): Select|Update|Delete
    {
        if ( empty($name) )
            return $this;

        if ( empty($this->where_params) )
            return $this->where($name, $value, $sign);

        $this->where .= " AND `{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }

    public function or(string $name, mixed $value, string $sign = "="): Select|Update|Delete
    {
        if ( empty($name) )
            return $this;

        if ( empty($this->where_params) )
            return $this->where($name, $value, $sign);

        $this->where .= " OR `{$name}`{$sign}?";
        $this->where_params[] = $value;

        return $this;
    }
}
