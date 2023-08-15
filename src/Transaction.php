<?php

namespace ModernPDO;

/**
 * Wrapper over transaction.
 *
 * Use ModerPDO objects to get this class object.
 */
class Transaction
{
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
        return $this->pdo->beginTransaction();
    }

    /**
     * Commits a transaction.
     *
     * @see https://php.net/manual/en/pdo.commit.php
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * Rolls back a transaction.
     *
     * @see https://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
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
