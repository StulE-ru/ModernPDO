<?php

namespace ModernPDO;

/**
 * Wrapper over PDOStatement.
 *
 * Use ModerPDO objects to get this class object.
 */
class Statement
{
    public function __construct(
        private ?\PDOStatement $statement,
    ) {
    }

    /**
     * Returns the number of rows affected by the last SQL statement.
     *
     * @see https://www.php.net/manual/en/pdostatement.rowcount.php
     */
    public function rowCount(): int
    {
        try {
            $count = $this->statement?->rowCount() ?? 0;
        } catch (\Throwable $th) {
            $count = 0;
        }

        return $count;
    }

    /**
     * Fetches the next row from a result set.
     *
     * @return array<string, mixed> If successful, an array of data, otherwise an empty array
     *
     * @see https://www.php.net/manual/en/pdostatement.fetch.php
     */
    public function fetch(): array
    {
        try {
            /** @var array<string, mixed>|false */
            $row = $this->statement?->fetch(\PDO::FETCH_ASSOC) ?? false;
        } catch (\Throwable $th) {
            $row = false;
        }

        return $row !== false ? $row : [];
    }

    /**
     * Fetches the remaining rows from a result set.
     *
     * @return list<array<string, mixed>> If successful, an array of arrays, otherwise an empty array
     *
     * @see https://www.php.net/manual/en/pdostatement.fetchall.php
     */
    public function fetchAll(): array
    {
        try {
            /** @var list<array<string, mixed>>|false */
            $rows = $this->statement?->fetchAll(\PDO::FETCH_ASSOC) ?? false;
        } catch (\Throwable $th) {
            $rows = false;
        }

        return $rows !== false ? $rows : [];
    }
}
