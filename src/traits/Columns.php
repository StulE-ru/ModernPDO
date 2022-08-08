<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Select;

trait Columns
{
    protected string $columns = "*";

    public function columns(array $columns): Select
    {
        if ( empty($columns) )
            return $this;

        $this->columns = "";

        $last_key = array_key_last($columns);

        foreach ($columns as $key => $column)
        {
            $this->columns .= "`{$column}`";

            if ( $last_key !== $key )
                $this->columns .= ", ";
        }

        return $this;
    }
}
