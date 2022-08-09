<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Select;

trait Columns
{
    protected string $columns = "*";

    /**
     * @brief Добавление столбцов в стиле SELECT `col1`, `col2`, etc.
     *
     * @param array $columns - массив столбцов [$col1, $col2, ...]
     */
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
