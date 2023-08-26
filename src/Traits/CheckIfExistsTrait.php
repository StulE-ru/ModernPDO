<?php

namespace ModernPDO\Traits;

/**
 * Trait for working with check status.
 */
trait CheckIfExistsTrait
{
    /**
     * @var bool check status
     */
    protected bool $checkIfExists = false;

    /**
     * Set checkIfExists status.
     *
     * @param bool $check check status
     *
     * @return $this
     */
    public function checkIfExists(bool $check = true): object
    {
        $this->checkIfExists = $check;

        return $this;
    }
}
