<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;

/**
 * Trait for working with 'joins'.
 */
trait JoinsTrait
{
    use OnTrait;

    /**
     * @var string join table name
     */
    protected string $joinsTable = '';

    /**
     * @var string join type
     */
    protected string $joinsType = '';

    /**
     * Returns set query.
     */
    protected function joinsQuery(Escaper $escaper): string
    {
        if ($this->joinsTable === '') {
            return '';
        }

        $query = $this->joinsType . ' ' . $escaper->table($this->joinsTable);

        if (!empty($this->on)) {
            $query .= ' ' . $this->onQuery($escaper);
        }

        return $query;
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function joinsPlaceholders(): array
    {
        if ($this->joinsTable === '') {
            return [];
        }

        return $this->onPlaceholders();
    }

    /**
     * Set inner join.
     *
     * @param string $table join table name
     *
     * @return $this
     */
    public function innerJoin(string $table): object
    {
        $this->joinsTable = $table;
        $this->joinsType = 'INNER JOIN';

        return $this;
    }

    /**
     * Set left outer join.
     *
     * @param string $table join table name
     *
     * @return $this
     */
    public function leftJoin(string $table): object
    {
        $this->joinsTable = $table;
        $this->joinsType = 'LEFT OUTER JOIN';

        return $this;
    }

    /**
     * Set right outer join.
     *
     * @return $this
     */
    public function rightJoin(string $table): object
    {
        $this->joinsTable = $table;
        $this->joinsType = 'RIGHT OUTER JOIN';

        return $this;
    }
}
