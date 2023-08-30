<?php

namespace ModernPDO\Drivers\Escapers;

use ModernPDO\Escaper;

/**
 * SQLite3 wrapper over Escaper.
 */
class SQLite3Escaper extends Escaper
{
    /**
     * @var string character for quote
     */
    private const QUOTE = '`';

    /**
     * Escapes and returns table name.
     */
    public function table(string $name): string
    {
        $pieces = explode('.', $name);

        if (\count($pieces) > 1) {
            foreach ($pieces as $key => $piece) {
                $pieces[$key] = self::table($piece);
            }

            return implode('.', $pieces);
        }

        $name = parent::table($name);

        $name = str_replace('`', '``', $name);

        return self::QUOTE . $name . self::QUOTE;
    }

    /**
     * Escapes and returns column name.
     */
    public function column(string $name): string
    {
        $pieces = explode('.', $name);

        if (\count($pieces) > 1) {
            foreach ($pieces as $key => $piece) {
                $pieces[$key] = self::column($piece);
            }

            return implode('.', $pieces);
        }

        $name = parent::column($name);

        $name = str_replace('`', '``', $name);

        return self::QUOTE . $name . self::QUOTE;
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
