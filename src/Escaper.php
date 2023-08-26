<?php

namespace ModernPDO;

/**
 * Class for escape strings.
 */
class Escaper
{
    /**
     * Escaper constructor.
     */
    public function __construct(
        private \PDO $pdo,
    ) {
    }

    /**
     * Escapes and returns string.
     */
    private function string(string $string): string
    {
        return mb_substr($this->pdo->quote($string), 1, -1);
    }

    /**
     * Escapes and returns table name.
     */
    public function table(string $name): string
    {
        return $this->string($name);
    }

    /**
     * Escapes and returns column name.
     */
    public function column(string $name): string
    {
        return $this->string($name);
    }

    /**
     * Escapes and returns key name.
     */
    public function key(string $name): string
    {
        return $this->string($name);
    }

    /**
     * Escapes and returns field value.
     */
    public function value(string $name): string
    {
        return $this->string($name);
    }
}
