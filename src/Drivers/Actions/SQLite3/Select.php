<?php

namespace ModernPDO\Drivers\Actions\SQLite3;

use ModernPDO\Actions\Select as BaseSelect;

/**
 * Class for getting rows from a table.
 */
class Select extends BaseSelect
{
    /**
     * Set right outer join.
     *
     * @return $this
     *
     * @throws \Exception Database does not support this feature
     */
    public function rightJoin(string $table): object
    {
        throw new \Exception('SQLite3 does not support this feature');
    }
}
