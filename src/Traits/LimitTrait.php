<?php

namespace ModernPDO\Traits;

use ModernPDO\Escaper;

/**
 * Trait for working with 'limit'.
 */
trait LimitTrait
{
    /**
     * @var int limit count
     */
    protected int $limitCount = 0;

    /**
     * @var int limit offset
     */
    protected int $limitOffset = 0;

    /**
     * Returns set query.
     */
    protected function limitQuery(Escaper $escaper): string
    {
        if ($this->limitCount <= 0) {
            return '';
        }

        $query = 'LIMIT ' . $this->limitCount;

        if ($this->limitOffset > 0) {
            $query .= ' OFFSET ' . $this->limitOffset;
        }

        return $query;
    }

    /**
     * Returns set placeholders.
     *
     * @return list<mixed>
     */
    protected function limitPlaceholders(): array
    {
        return [];
    }

    /**
     * Set values for limit.
     *
     * @param int $count  count of rows. It must be greater than 0
     * @param int $offset start rows offset. It must be greater than 0
     *
     * @return $this
     */
    public function limit(int $count, int $offset = 0): object
    {
        $this->limitCount = $count;
        $this->limitOffset = $offset;

        return $this;
    }
}
