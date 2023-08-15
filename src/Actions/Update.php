<?php

namespace ModernPDO\Actions;

use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use ModernPDO\Traits\SetTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * @brief Класс обновления записи(-ей) из таблицы.
 */
final class Update
{
    use SetTrait;
    use WhereTrait;

    /** The SQL statement. */
    protected string $query;

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
        return '';
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
     */
    protected function getPlaceholders(): array
    {
        return array_merge($this->set_params, $this->where_params);
    }

    /**
     * Executes query and returns statement.
     */
    protected function exec(): Statement
    {
        return $this->mpdo->query(
            $this->getQuery(),
            $this->getPlaceholders(),
        );
    }

    /**
     * @brief Обновление записи(-ей) из таблицы.
     *
     * @return bool в случае успеха true, иначе false
     */
    public function execute(): bool
    {
        if (empty($this->set)) {
            return false;
        }

        $this->query = 'UPDATE ' . $this->table . ' SET ' . $this->set . ' WHERE ' . $this->where;

        return $this->exec()->rowCount() > 0;
    }
}
