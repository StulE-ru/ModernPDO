<?php

namespace ModernPDO\Actions;

use ModernPDO\Escaper;
use ModernPDO\ModernPDO;
use ModernPDO\Statement;

/**
 * Base class for all actions.
 */
abstract class Action
{
    /** The SQL statement. */
    protected string $query = '';

    public function __construct(
        protected ModernPDO $mpdo,
        protected Escaper $escaper,
        protected string $table,
    ) {
    }

    /**
     * Returns base query.
     */
    abstract protected function buildQuery(): string;

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
    abstract protected function getPlaceholders(): array;

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
}
