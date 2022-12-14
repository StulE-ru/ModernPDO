<?php

namespace ModernPDO\Actions;

//
// Подключение пространств имен.
//

use ModernPDO\Traits\Where;

/**
 * @brief Класс удаления записи(-ей) из таблицы.
 */
final class Delete
{
    // Подключение трейтов.
    use Where;

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
     * @brief Удаление записи(-ей) из таблицы.
     *
     * @return bool В случае успеха true, иначе false.
     */
    public function execute(): bool
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM `{$this->table}` WHERE {$this->where}"
        );

        return ( $statement && $statement->execute($this->getParams()) );
    }
}
