<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Int table field.
 */
class IntField extends Field
{
    /**
     * Int constructor.
     *
     * @param string $name field name
     * @param bool $unsigned field value is positive
     * @param bool $canBeNull field value can be null
     * @param int|null|false $default default field value
     *
     * If $canBeNull is false $default must be integer or false.
     * If $default is false, it means that no default value will be set.
     */
    public function __construct(
        private string $name,
        private bool $unsigned = false,
        private bool $canBeNull = false,
        private int|null|bool $default = false,
    ) {
    }

    /**
     * Returns field query.
     */
    public function build(Escaper $escaper): string
    {
        $query = $escaper->column($this->name) . ' INT';

        // Check unsigned

        if ($this->unsigned) {
            $query .= ' UNSIGNED';
        }

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
