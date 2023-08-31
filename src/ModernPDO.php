<?php

namespace ModernPDO;

use ModernPDO\Actions\AlterTable;
use ModernPDO\Actions\CreateTable;
use ModernPDO\Actions\Delete;
use ModernPDO\Actions\DropTable;
use ModernPDO\Actions\Insert;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * Wrapper over PDO.
 */
class ModernPDO
{
    /**
     * @var \PDO pdo object
     */
    private \PDO $pdo;

    /**
     * @var Escaper escaper object
     */
    private Escaper $escaper;

    /**
     * @var Factory factory object
     */
    private Factory $factory;

    /**
     * ModernPDO constructor.
     *
     * @param \PDO $pdo PDO object
     * @param class-string $escaper Full escaper class name
     * @param class-string $factory Full factory class name
     */
    public function __construct(
        \PDO $pdo,
        string $escaper = Escaper::class,
        string $factory = Factory::class,
    ) {
        $this->pdo = $pdo;
        $this->escaper = new $escaper($pdo);
        $this->factory = new $factory($pdo, $this);
    }

    /**
     * Returns Escaper object.
     */
    public function escaper(): Escaper
    {
        return $this->escaper;
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
     * Returns CreateTable object.
     */
    public function createTable(string $table): CreateTable
    {
        return $this->factory->createTable($table);
    }

    /**
     * Returns AlterTable object.
     */
    public function alterTable(string $table): AlterTable
    {
        return $this->factory->alterTable($table);
    }

    /**
     * Returns DropTable object.
     */
    public function dropTable(string $table): DropTable
    {
        return $this->factory->dropTable($table);
    }

    /**
     * Returns Select object.
     */
    public function select(string $table): Select
    {
        return $this->factory->select($table);
    }

    /**
     * Returns Insert object.
     */
    public function insert(string $table): Insert
    {
        return $this->factory->insert($table);
    }

    /**
     * Returns Update object.
     */
    public function update(string $table): Update
    {
        return $this->factory->update($table);
    }

    /**
     * Returns Delete object.
     */
    public function delete(string $table): Delete
    {
        return $this->factory->delete($table);
    }
}
