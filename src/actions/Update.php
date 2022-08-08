<?php

namespace ModernPDO\Actions;

//
// Подключение пространств имен.
//

use ModernPDO\Traits\Set;
use ModernPDO\Traits\Where;

/**
 * @brief Класс обновления записи(-ей) из таблицы.
 */
final class Update
{
    // Подключение трейтов.
    use Set, Where;

    /**
     * @brief Конструктор класса.
     *
     * @param[in] $pdo - инициализированный объект класса PDO.
     * @param[in] $table - название таблицы.
     */
    public function __construct(
        private \PDO $pdo,
        private string $table,
    ) {}

    /**
     * @brief Получение параметров.
     *
     * @return Массив параметров.
     */
    private function getParams(): array
    {
        return array_merge($this->set_params, $this->where_params);
    }

    /**
     * @brief Обновление записи(-ей) из таблицы.
     *
     * @return В случае успеха true, иначе false.
     */
    public function execute(): bool
    {
        if ( empty($this->set) )
            return false;

        $statement = $this->pdo->prepare(
            "UPDATE `{$this->table}` SET {$this->set} WHERE {$this->where}"
        );

        return ( $statement && $statement->execute($this->getParams()) );
    }
}
