<?php

namespace ModernPDO\Drivers\Escapers;

use ModernPDO\Escaper;

/**
 * MySQL wrapper over Escaper.
 */
class MySQLEscaper extends Escaper
{
    /**
     * @var string character for quote.
     */
    private const QUOTE = '`';

    /**
     * Escapes and returns table name.
     */
    public function table(string $name): string
    {
        return self::QUOTE . parent::table($name) . self::QUOTE;
    }

    /**
     * Escapes and returns column name.
     */
    public function column(string $name): string
    {
        return self::QUOTE . parent::column($name) . self::QUOTE;
    }

    /**
     * Escapes and returns key name.
     */
    public function key(string $name): string
    {
        return self::QUOTE . parent::key($name) . self::QUOTE;
    }

    /**
     * Escapes and returns string field value.
     */
    public function stringValue(string $value): string
    {
        return '\'' . parent::stringValue($value) . '\'';
    }

    /**
     * Escapes and returns bool field value.
     */
    public function boolValue(bool $value): string
    {
        return parent::boolValue($value);
    }
}
