<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Bool table field.
 */
class BoolField extends Field
{
    /**
     * BoolField constructor.
     *
     * @param string $name field name
     * @param bool $canBeNull field value can be null
     * @param bool|null|int $default default field value
     *
     * If $canBeNull is false $default must be bool, 0, 1 or -1.
     * If $default is 0 or 1, it will be converted to bool.
     * If $default is -1, it means that no default value will be set.
     */
    public function __construct(
        private string $name,
        private bool $canBeNull = false,
        private bool|null|int $default = -1,
    ) {
    }

    /**
     * Returns field query.
     */
    public function build(Escaper $escaper): string
    {
        $query = $escaper->column($this->name) . ' BIT';

        // Check can be null

        if (!$this->canBeNull) {
            $query .= ' NOT';

            if ($this->default === null) {
                $this->default = -1;
            }
        }

        $query .= ' NULL';

        // Check default

        $this->default = match ($this->default) {
            0 => false,
            1 => true,
            default => $this->default,
        };

        if (!is_int($this->default)) {
            $query .= ' DEFAULT ';

            if ($this->default === null) {
                $query .= 'NULL';
            } else {
                $query .= $escaper->boolValue($this->default);
            }
        }

        return $query;
    }
}
