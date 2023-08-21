<?php

namespace ModernPDO\Actions;

use ModernPDO\Traits\ColumnsTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for getting rows from a table.
 */
class Select extends Action
{
    use ColumnsTrait;
    use WhereTrait;

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return trim('SELECT ' . $this->columnsQuery() . ' FROM ' . $this->table . ' ' . $this->where);
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return array_merge($this->columnsPlaceholders(), $this->where_params);
    }

    /**
     * Executes query and returns one row.
     *
     * @return array<string, mixed>
     */
    protected function getOne(): array
    {
        return $this->exec()->fetch();
    }

    /**
     * Executes query and returns all rows.
     *
     * @return list<array<string, mixed>>
     */
    protected function getAll(): array
    {
        return $this->exec()->fetchAll();
    }

    /**
     * Returns all rows from table.
     *
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        $this->query = $this->buildQuery();

        return $this->getAll();
    }

    /**
     * Returns one row from table.
     *
     * @return array<string, mixed>
     */
    public function one(): array
    {
        $this->query = $this->buildQuery() . ' LIMIT 1';

        return $this->getOne();
    }

    /**
     * Returns first row from table.
     *
     * @param string $order column name to sort rows
     *
     * @return array<string, mixed>
     */
    public function firstBy(string $order): array
    {
        $this->query = $this->buildQuery() . ' ORDER BY ' . $order . ' ASC LIMIT 1';

        return $this->getOne();
    }

    /**
     * Returns last row from table.
     *
     * @param string $order column name to sort rows
     *
     * @return array<string, mixed>
     */
    public function lastBy(string $order): array
    {
        $this->query = $this->buildQuery() . ' ORDER BY ' . $order . ' DESC LIMIT 1';

        return $this->getOne();
    }
}
