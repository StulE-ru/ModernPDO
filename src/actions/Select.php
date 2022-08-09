<?php

namespace ModernPDO\Actions;

//
// Подключение пространств имен.
//

use ModernPDO\Traits\Columns;
use ModernPDO\Traits\Where;

/**
 * @brief Класс получения записи(-ей) из таблицы.
 */
final class Select
{
    // Подключение трейтов.
    use Columns, Where;

    /**
     * @brief Конструктор класса.
     *
     * @param \PDO $pdo - инициализированный объект класса PDO.
     * @param string $table - название таблицы.
     */
    public function __construct(
        private \PDO $pdo,
        private string $table,
    ) {}

    /**
     * @brief Получение параметров.
     *
     * @return array Массив параметров.
     */
    private function getParams(): array
    {
        return $this->where_params;
    }

    /**
     * @brief Получение записей из таблицы.
     *
     * @param string $query - SQL-запрос.
     *
     * @return ?array В случае успеха массив записей, иначе null.
     */
    private function getAll(string $query): ?array
    {
        $statement = $this->pdo->prepare($query);

        if ( $statement && $statement->execute($this->getParams()) )
        {
            $data = $statement->fetchAll();

            if ( is_array($data) )
                return $data;
        }

        return null;
    }

    /**
     * @brief Получение записи из таблицы.
     *
     * @param string $query - SQL-запрос.
     *
     * @return ?array В случае успеха массив записи, иначе null.
     */
    private function getOne(string $query): ?array
    {
        $statement = $this->pdo->prepare($query);

        if ( $statement && $statement->execute($this->getParams()) )
        {
            $data = $statement->fetch();

            if ( is_array($data) )
                return $data;
        }

        return null;
    }

    /**
     * @brief Получение записей из таблицы.
     *
     * @return ?array В случае успеха массив записей, иначе null.
     */
    public function all(): ?array
    {
        return $this->getAll(
            "SELECT {$this->columns} FROM `{$this->table}` WHERE {$this->where}"
        );
    }

    /**
     * @brief Получение записи из таблицы.
     *
     * @return ?array В случае успеха массив записи, иначе null.
     */
    public function one(): ?array
    {
        return $this->getOne(
            "SELECT {$this->columns} FROM `{$this->table}` WHERE {$this->where} LIMIT 1"
        );
    }

    /**
     * @brief Получение первой записи из таблицы.
     *
     * @param string $order - столбец, по которому сортировать записи.
     *
     * @return ?array В случае успеха массив записи, иначе null.
     */
    public function firstBy(string $order): ?array
    {
        return $this->getOne(
            "SELECT {$this->columns} FROM `{$this->table}` WHERE {$this->where} ORDER BY {$order} ASC LIMIT 1"
        );
    }

    /**
     * @brief Получение последней записи из таблицы.
     *
     * @param string $order - столбец, по которому сортировать записи.
     *
     * @return ?array В случае успеха массив записи, иначе null.
     */
    public function lastBy(string $order): ?array
    {
        return $this->getOne(
            "SELECT {$this->columns} FROM `{$this->table}` WHERE {$this->where} ORDER BY {$order} DESC LIMIT 1"
        );
    }
}
