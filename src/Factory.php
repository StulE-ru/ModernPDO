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
    public function select(Escaper $escaper, string $table): Select
    {
        return new Select($this->mpdo, $escaper, $table);
    }

    /**
     * Returns Insert object.
     */
    public function insert(Escaper $escaper, string $table): Insert
    {
        return new Insert($this->mpdo, $escaper, $table);
    }

    /**
     * Returns Update object.
     */
    public function update(Escaper $escaper, string $table): Update
    {
        return new Update($this->mpdo, $escaper, $table);
    }

    /**
     * Returns Delete object.
     */
    public function delete(Escaper $escaper, string $table): Delete
    {
        return new Delete($this->mpdo, $escaper, $table);
    }
}
