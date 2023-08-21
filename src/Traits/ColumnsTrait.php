<?php

namespace ModernPDO\Traits;

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
     *
     * @return $this
     */
    public function columns(array $columns): object
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
