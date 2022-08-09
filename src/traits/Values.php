<?php

namespace ModernPDO\Traits;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Insert;

trait Values
{
    protected string $columns = "";
    protected string $values = "";

    protected array $values_params = [];

    /**
     * @brief Добавление значений для INSERT.
     *
     * @param array $values - массив значений [$col1 => $val1, ...]
     */
    public function values(array $values): Insert
    {
        if ( empty($values) )
            return $this;

        $this->columns = "";
        $this->values = "";

        $this->values_params = [];

        $last_key = array_key_last($values);

        foreach ($values as $column => $value)
        {
            $this->columns .= "`{$column}`";
            $this->values .= "?";

            $this->values_params[] = $value;

            if ( $last_key !== $column )
            {
                $this->columns .= ", ";
                $this->values .= ", ";
            }
        }

        return $this;
    }
}
