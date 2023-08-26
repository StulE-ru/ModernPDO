<?php

namespace ModernPDO;

/**
 * Wrapper over transaction.
 *
 * Use ModerPDO objects to get this class object.
 */
class Transaction
{
    /**
     * Transaction constructor.
     */
    public function __construct(
        private \PDO $pdo,
    ) {
    }

    /**
     * Initiates a transaction.
     *
     * @see https://php.net/manual/en/pdo.begintransaction.php
     */
    public function begin(): bool
    {
        try {
            return $this->pdo->beginTransaction();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Commits a transaction.
     *
     * @see https://php.net/manual/en/pdo.commit.php
     */
    public function commit(): bool
    {
        try {
            return $this->pdo->commit();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Rolls back a transaction.
     *
     * @see https://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack(): bool
    {
        try {
            return $this->pdo->rollBack();
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Checks if inside a transaction.
     *
     * @see https://php.net/manual/en/pdo.intransaction.php
     */
    public function isActive(): bool
    {
        return $this->pdo->inTransaction();
    }
}
