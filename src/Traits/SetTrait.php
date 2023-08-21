<?php

namespace ModernPDO\Traits;

/**
 * Trait for working with 'where'.
 */
trait SetTrait
{
    /** List of set. */
    protected string $set = '';

    /**
     * Array of placeholders.
     *
     * @var mixed[]
     */
    protected array $set_params = [];

    /**
     * Set values for SET.
     *
     * @param string[] $values array of values for SET
     *
     * @return $this
     */
    public function set(array $values): object
    {
        if (empty($values)) {
            return $this;
        }

        $this->set = '';
        $this->set_params = [];

        $last_key = array_key_last($values);

        foreach ($values as $column => $value) {
            $this->set .= $column . '=?';

            $this->set_params[] = $value;

            if ($last_key !== $column) {
                $this->set .= ', ';
            }
        }

        return $this;
    }
}
