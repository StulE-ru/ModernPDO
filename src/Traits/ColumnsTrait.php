<?php

namespace ModernPDO\Traits;

use ModernPDO\Actions\Select;

/**
 * Trait for working with 'columns'.
 */
trait ColumnsTrait
{
    /** List of columns. */
    protected string $columns = '*';

    /**
     * Set columns.
     *
     * @param string[] $columns array of column names
     */
    public function columns(array $columns): Select
    {
        if (empty($columns)) {
            return $this;
        }

        $this->columns = '';

        $last_key = array_key_last($columns);

        foreach ($columns as $key => $column) {
            $this->columns .= $column;

            if ($last_key !== $key) {
                $this->columns .= ', ';
            }
        }

        return $this;
    }
}
