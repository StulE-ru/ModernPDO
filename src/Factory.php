<?php

namespace ModernPDO;

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Insert;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * Factory for customize ModerPDO.
 */
class Factory
{
    /**
     * Factory constructor.
     */
    public function __construct(
        private \PDO $pdo,
        private ModernPDO $mpdo,
    ) {
    }

    /**
     * Returns Transaction object.
     */
    public function transaction(): Transaction
    {
        return new Transaction($this->pdo);
    }

    /**
     * Returns Statement object.
     */
    public function statement(?\PDOStatement $statement): Statement
    {
        return new Statement($statement);
    }

    /**
     * Returns Select object.
     */
    public function select(string $table): Select
    {
        return new Select($this->mpdo, $table);
    }

    /**
     * Returns Insert object.
     */
    public function insert(string $table): Insert
    {
        return new Insert($this->mpdo, $table);
    }

    /**
     * Returns Update object.
     */
    public function update(string $table): Update
    {
        return new Update($this->mpdo, $table);
    }

    /**
     * Returns Delete object.
     */
    public function delete(string $table): Delete
    {
        return new Delete($this->mpdo, $table);
    }
}
