<?php

namespace ModernPDO\Actions;

use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use ModernPDO\Traits\ColumnsTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for getting rows from a table.
 */
class Select
{
    use ColumnsTrait;
    use WhereTrait;

    /** The SQL statement. */
    protected string $query = '';

    public function __construct(
        protected ModernPDO $mpdo,
        protected string $table,
    ) {
    }

    /**
     * Returns base query.
     */
    protected function buildQuery(): string
    {
        return 'SELECT ' . $this->columns . ' FROM ' . $this->table . ' ' . $this->where;
    }

    /**
     * Returns the SQL statement.
     */
    protected function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Returns placeholders.
     *
     * @return mixed[]
     */
    protected function getPlaceholders(): array
    {
        return $this->where_params;
    }

    /**
     * Executes query and returns statement.
     */
    protected function execute(): Statement
    {
        return $this->mpdo->query(
            $this->getQuery(),
            $this->getPlaceholders(),
        );
    }

    /**
     * Executes query and returns one row.
     *
     * @return array<string, mixed>
     */
    protected function getOne(): array
    {
        return $this->execute()->fetch();
    }

    /**
     * Executes query and returns all rows.
     *
     * @return list<array<string, mixed>>
     */
    protected function getAll(): array
    {
        return $this->execute()->fetchAll();
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
