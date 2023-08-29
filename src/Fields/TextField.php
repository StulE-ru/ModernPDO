<?php

namespace ModernPDO\Fields;

use ModernPDO\Escaper;

/**
 * Text table field.
 */
class TextField extends Field
{
    /**
     * Text constructor.
     *
     * @param string            $name      field name
     * @param bool              $canBeNull field value can be null
     * @param string|false|null $default   default field value
     *
     * If $canBeNull is false $default must be string or false.
     * If $default is false, it means that no default value will be set.
     */
    public function __construct(
        private string $name,
        private bool $canBeNull = false,
        private string|null|bool $default = false,
    ) {
    }

    /**
     * Returns field query.
     */
    public function build(Escaper $escaper): string
    {
        $query = $escaper->column($this->name) . ' TEXT';

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
