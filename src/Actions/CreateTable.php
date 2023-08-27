<?php

namespace ModernPDO\Actions;

use ModernPDO\Fields\Field;
use ModernPDO\Keys\Key;
use ModernPDO\Traits\CheckIfExistsTrait;

/**
 * Class for creating tables.
 */
class CreateTable extends Action
{
    use CheckIfExistsTrait;

    /**
     * @var Field[] $fields table fields
     */
    protected array $fields = [];

    /**
     * @var Key[] $keys table keys
     */
    protected array $keys = [];

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'CREATE TABLE';

        if ($this->checkIfExists) {
            $query .= ' IF NOT EXISTS';
        }

        $query .= ' ' . $escaper->table($this->table);

        $query .= ' (';

        foreach ($this->fields as $field) {
            $query .= $field->build($escaper) . ', ';
        }

        foreach ($this->keys as $key) {
            $query .= $key->build($escaper) . ', ';
        }

        $query = substr($query, 0, -2) . ')';

        return $query;
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return [];
    }

    /**
     * Set table fields.
     *
     * @param Field[] $fields table fields
     */
    public function fields(array $fields): CreateTable
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * Set table keys.
     *
     * @param Key[] $keys table keys
     */
    public function keys(array $keys): CreateTable
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Inserts row into table.
     */
    public function execute(): bool
    {
        if (empty($this->fields)) {
            return false;
        }

        $this->query = $this->buildQuery();

        return $this->exec()->status();
    }
}
