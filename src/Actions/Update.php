<?php

namespace ModernPDO\Actions;

use ModernPDO\ModernPDO;
use ModernPDO\Statement;
use ModernPDO\Traits\SetTrait;
use ModernPDO\Traits\WhereTrait;

/**
 * Class for updating rows from a table.
 */
class Update
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
        return 'UPDATE ' . $this->table . ' SET ' . $this->set . ' ' . $this->where;
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
     * Updates rows in table.
     */
    public function execute(): bool
    {
        if (empty($this->set)) {
            return false;
        }

        $this->query = $this->buildQuery();

        return $this->exec()->rowCount() > 0;
    }
}
