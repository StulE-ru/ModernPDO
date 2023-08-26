<?php

namespace ModernPDO\Drivers\Escapers;

use ModernPDO\Escaper;

/**
 * SQLite3 wrapper over Escaper.
 */
class SQLite3Escaper extends Escaper
{
    /**
     * @var string character for quote.
     */
    private const QUOTE = '"';

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
}
