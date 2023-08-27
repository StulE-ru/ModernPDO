<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Real table field.
 */
class RealField extends Field
{
    /**
     * Real constructor.
     *
     * @param string $name field name
     * @param bool $canBeNull field value can be null
     * @param float|null|false $default default field value
     *
     * If $canBeNull is false $default must be float or false.
     * If $default is false, it means that no default value will be set.
     */
    public function __construct(
        private string $name,
        private bool $canBeNull = false,
        private float|null|bool $default = false,
    ) {
    }

    /**
     * Returns field query.
     */
    public function build(Escaper $escaper): string
    {
        $query = $escaper->column($this->name) . ' REAL';

        // Check can be null

        if (!$this->canBeNull) {
            $query .= ' NOT';

            if ($this->default === null) {
                $this->default = false;
            }
        }

        $query .= ' NULL';

        // Check default

        if ($this->default !== false) {
            $query .= ' DEFAULT ';

            if ($this->default === null) {
                $query .= 'NULL';
            } else {
                $query .= $this->default;
            }
        }

        return $query;
    }
}
