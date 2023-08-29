<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Varchar table field.
 */
class VarcharField extends Field
{
    /**
     * Varchar constructor.
     *
     * @param string            $name      field name
     * @param string            $size      max value size
     * @param bool              $canBeNull field value can be null
     * @param string|false|null $default   default field value
     *
     * If $canBeNull is false $default must be string or false.
     * If $default is false, it means that no default value will be set.
     */
    public function __construct(
        private string $name,
        private int $size,
        private bool $canBeNull = false,
        private string|null|bool $default = false,
    ) {
    }

    /**
     * Returns field query.
     */
    public function build(Escaper $escaper): string
    {
        $query = $escaper->column($this->name) . ' VARCHAR(' . $this->size . ')';

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
                $query .= $escaper->stringValue($this->default);
            }
        }

        return $query;
    }
}
