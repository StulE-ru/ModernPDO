<?php

namespace ModernPDO\Actions;

//
// Подключение пространств имен.
//

use ModernPDO\Traits\Values;

/**
 * @brief Класс создания записи(-ей) из таблицы.
 */
final class Insert
{
    // Подключение трейтов.
    use Values;

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
        return $this->values_params;
    }

    /**
     * @brief Создание записи(-ей) из таблицы.
     *
     * @return В случае успеха true, иначе false.
     */
    public function execute(): bool
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO `{$this->table}` ({$this->columns}) VALUES ({$this->values})"
        );

        return ( $statement && $statement->execute($this->getParams()) );
    }
}
