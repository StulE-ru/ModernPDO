<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Update;

trait Set
{
    protected string $set = "";
    protected array $set_params = [];

    public function set(array $values): Update
    {
        if ( empty($values) )
            return $this;

        $this->set = "";
        $this->set_params = [];

        $last_key = array_key_last($values);

        foreach ($values as $column => $value)
        {
            $this->set .= "`{$column}`=?";

            $this->set_params[] = $value;

            if ( $last_key !== $column )
                $this->set .= ", ";
        }

        return $this;
    }
}