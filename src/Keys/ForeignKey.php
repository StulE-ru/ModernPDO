<?php

namespace ModernPDO\Keys;

use ModernPDO\Escaper;

/**
 * Foreign table key.
 */
class ForeignKey extends Key
{
    /**
     * ForeignKey constructor.
     *
     * @param string|string[] $fields        string or string array of fields
     * @param string          $foreignTable  foreign table string
     * @param string|string[] $foreignFields string or string array of foreign fields
     * @param string          $name          key name if it is empty will be used generated by db
     */
    public function __construct(
        private string|array $fields,
        private string $foreignTable,
        private string|array $foreignFields,
        private string $name = '',
    ) {
    }

    /**
     * Builds and returns fields string.
     *
     * @param string|string[] $fields string or string array of fields
     */
    private function buildFields(Escaper $escaper, string|array $fields): string
    {
        if (\is_string($fields)) {
            return $escaper->column($fields);
        }

        $query = '';

        foreach ($fields as $field) {
            $query .= $escaper->column($field) . ', ';
        }

        return mb_substr($query, 0, -2);
    }

    /**
     * Returns key query.
     */
    public function build(Escaper $escaper): string
    {
        $query = '';

        if ($this->name !== '') {
            $query .= 'CONSTRAINT ' . $escaper->column($this->name) . ' ';
        }

        $query .= 'FOREIGN KEY (' . $this->buildFields($escaper, $this->fields) . ') ';
        $query .= 'REFERENCES ' . $escaper->table($this->foreignTable) . '(' . $this->buildFields($escaper, $this->foreignFields) . ')';

        return $query;
    }
}
