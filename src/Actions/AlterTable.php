<?php

namespace ModernPDO\Actions;

use ModernPDO\Fields\Field;

/**
 * Class for updating tables.
 */
class AlterTable extends Action
{
    /**
     * @var string new table name
     */
    protected string $newName = '';

    /**
     * @var Field[] array of new fields
     */
    protected array $addFields = [];

    /**
     * @var array<string, string> array of rename fields
     */
    protected array $renameFields = [];

    /**
     * @var string[] array of drop names
     */
    protected array $dropFields = [];

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        $escaper = $this->mpdo->escaper();

        $query = 'ALTER TABLE ' . $escaper->table($this->table);

        // Rename table

        if ($this->newName !== '') {
            $query .= ' RENAME TO ' . $escaper->table($this->newName) . ', ';
        }

        // Add columns

        if (!empty($this->addFields)) {
            foreach ($this->addFields as $field) {
                $query .= ' ADD COLUMN ' . $field->build($escaper) . ', ';
            }
        }

        // Rename columns

        if (!empty($this->renameFields)) {
            foreach ($this->renameFields as $name => $newName) {
                $query .= ' RENAME COLUMN ' . $escaper->column($name) . ' TO ' . $escaper->column($newName) . ', ';
            }
        }

        // Drop columns

        if (!empty($this->dropFields)) {
            foreach ($this->dropFields as $name) {
                $query .= ' DROP COLUMN ' . $escaper->column($name) . ', ';
            }
        }

        return substr($query, 0, -2);
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
     * Renames table.
     */
    public function rename(string $newName): self
    {
        $this->newName = $newName;

        return $this;
    }

    /**
     * Adds fields to add.
     *
     * @param Field[] $fields array of fields
     */
    public function addColumns(array $fields): self
    {
        $this->addFields = $fields;

        return $this;
    }

    /**
     * Adds fields to rename.
     *
     * @param array<string, string> $names array of names
     */
    public function renameColumns(array $names): self
    {
        $this->renameFields = $names;

        return $this;
    }

    /**
     * Adds fields to drop.
     *
     * @param string[] $names array of names
     */
    public function dropColumns(array $names): self
    {
        $this->dropFields = $names;

        return $this;
    }

    /**
     * Updates table.
     */
    public function execute(): bool
    {
        if (
            $this->newName === ''
            && empty($this->addFields)
            && empty($this->renameFields)
            && empty($this->dropFields)
        ) {
            return false;
        }

        $this->query = $this->buildQuery();

        return $this->exec()->status();
    }
}
