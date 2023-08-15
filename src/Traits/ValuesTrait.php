<?php

namespace ModernPDO\Traits;

use ModernPDO\Actions\Insert;

trait ValuesTrait
{
    /** List of columns. */
    protected string $columns = '';
    /** List of values. */
    protected string $values = '';

    /** Array of placeholders. */
    protected array $values_params = [];

    /**
     * Set values for VALUES.
     *
     * @param string[] $values array of values for VALUES
     */
    public function values(array $values): Insert
    {
        if (empty($values)) {
            return $this;
        }

        $this->columns = '';
        $this->values = '';

        $this->values_params = [];

        $last_key = array_key_last($values);

        foreach ($values as $column => $value) {
            $this->columns .= "`{$column}`";
            $this->values .= '?';

            $this->values_params[] = $value;

            if ($last_key !== $column) {
                $this->columns .= ', ';
                $this->values .= ', ';
            }
        }

        return $this;
    }
}
