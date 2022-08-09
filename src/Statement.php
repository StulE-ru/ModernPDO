<?php

namespace ModernPDO;

final class Statement
{
    /**
     * @brief Конструктор класса.
     *
     * @param ?\PDOStatement $statement - инициализированный объект класса PDOStatement или null.
     */
    public function __construct(
        private ?\PDOStatement $statement,
    ) {}

    /**
     * @brief Получение одной записи.
     *
     * @return array В случае успеха массив записи, иначе пустой массив.
     */
    public function fetch(): array
    {
        if ( empty($this->statement) )
            return [];

        $row = $this->statement->fetch();

        return is_array($row) ? $row : [];
    }

    /**
     * @brief Получение всех записей.
     *
     * @return array В случае успеха массив записей, иначе пустой массив.
     */
    public function fetchAll(): array
    {
        if ( empty($this->statement) )
            return [];

        $rows = $this->statement->fetchAll();

        return is_array($rows) ? $rows : [];
    }
}
