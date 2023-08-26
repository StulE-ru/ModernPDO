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
        private ?Escaper $escaper = null,
        private ?Factory $factory = null,
    ) {
        if ($this->escaper === null) {
            $this->escaper = new Escaper($pdo);
        }

        if ($this->factory === null) {
            $this->factory = new Factory($pdo, $this);
        }
    }

    /**
     * Returns Transaction object.
     */
    public function transaction(): Transaction
    {
        return $this->factory->transaction();
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
        try {
            $count = $this->pdo->exec($query);
        } catch (\Throwable $th) {
            $count = false;
        }

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
        try {
            if (empty($values)) {
                $statement = $this->pdo->query($query);
            } else {
                $statement = $this->pdo->prepare($query);

                if ($statement !== false) {
                    $statement->execute($values);
                }
            }
        } catch (\Throwable $th) {
            $statement = false;
        }

        return $this->factory->statement($statement !== false ? $statement : null);
    }

    /**
     * Returns Select object.
     */
    public function select(string $table): Select
    {
        return $this->factory->select($this->escaper, $table);
    }

    /**
     * Returns Insert object.
     */
    public function insert(string $table): Insert
    {
        return $this->factory->insert($this->escaper, $table);
    }

    /**
     * Returns Update object.
     */
    public function update(string $table): Update
    {
        return $this->factory->update($this->escaper, $table);
    }

    /**
     * Returns Delete object.
     */
    public function delete(string $table): Delete
    {
        return $this->factory->delete($this->escaper, $table);
    }
}
