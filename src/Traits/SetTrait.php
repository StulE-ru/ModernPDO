<?php

namespace ModernPDO\Traits;

use ModernPDO\Actions\Update;

/**
 * Trait for working with 'where'.
 */
trait SetTrait
{
    /** List of set. */
    protected string $set = '';
    /** Array of placeholders. */
    protected array $set_params = [];

    /**
     * Set values for SET.
     *
     * @param string[] $values array of values for SET
     */
    public function set(array $values): Update
    {
        if (empty($values)) {
            return $this;
        }

        $this->set = '';
        $this->set_params = [];

        $last_key = array_key_last($values);

        foreach ($values as $column => $value) {
            $this->set .= "`{$column}`=?";

            $this->set_params[] = $value;

            if ($last_key !== $column) {
                $this->set .= ', ';
            }
        }

        return $this;
    }
}
