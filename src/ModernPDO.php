<?php

namespace ModernPDO;

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Insert;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * Wrapper over PDO.
 */
class ModernPDO
{
    /**
     * ModernPDO constructor.
     */
    public function __construct(
        private \PDO $pdo,
    ) {
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    /**
     * Returns Transaction object.
     */
    public function transaction(): Transaction
    {
        return new Transaction($this->pdo);
    }

    /**
     * Execute an SQL statement and return the number of affected rows.
     *
     * @param string $query The SQL statement to execute
     *
     * @see https://www.php.net/manual/en/pdo.exec.php
     */
    public function exec(string $query): int
    {
        $count = $this->pdo->exec($query);

        return $count !== false ? $count : 0;
    }

    /**
     * Prepares and executes an SQL statement.
     *
     * @param string  $query  The SQL statement to prepare and execute
     * @param mixed[] $values Placeholders that will replace '?'
     *
     * @see https://www.php.net/manual/en/pdo.query.php
     * @see https://www.php.net/manual/en/pdo.prepare.php
     * @see https://www.php.net/manual/en/pdostatement.execute.php
     */
    public function query(string $query, array $values = []): Statement
    {
        if (empty($values)) {
            $statement = $this->pdo->query($query);
        } else {
            $statement = $this->pdo->prepare($query);

            if ($statement !== false) {
                $statement->execute($values);
            }
        }

        return new Statement($statement !== false ? $statement : null);
    }

    /**
     * Returns Select object.
     */
    public function select(string $table): Select
    {
        return new Select($this, $table);
    }

    /**
     * Returns Insert object.
     */
    public function insert(string $table): Insert
    {
        return new Insert($this, $table);
    }

    /**
     * Returns Update object.
     */
    public function update(string $table): Update
    {
        return new Update($this, $table);
    }

    /**
     * Returns Delete object.
     */
    public function delete(string $table): Delete
    {
        return new Delete($this, $table);
    }
}
