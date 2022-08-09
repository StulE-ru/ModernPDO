<?php

namespace ModernPDO;

//
// Подключение файлов библиотеки.
//

require_once "traits/Columns.php";
require_once "traits/Set.php";
require_once "traits/Values.php";
require_once "traits/Where.php";

require_once "actions/Delete.php";
require_once "actions/Insert.php";
require_once "actions/Select.php";
require_once "actions/Update.php";

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Insert;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * @brief Класс работы с базой данных типа MySQL.
 */
final class ModernPDO
{
    /** Объект класса PDO. */
    private \PDO $pdo;

    /**
     * @brief Конструктор класса.
     *
     * @param[in] $charset - используемая кодировка.
     * @param[in] $charset - используемая кодировка.
     * @param[in] $host - хост базы данных.
     * @param[in] $username - имя пользователя базы данных.
     * @param[in] $password - пароль пользователя базы данных.
     * @param[in] $database - название базы данных.
     */
    public function __construct(
        string $type,
        string $charset,
        string $host,
        string $username,
        string $password,
        string $database,
    ) {
        try {
            $this->pdo = new \PDO("{$type}:host={$host};dbname={$database}", $username, $password);

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
            $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

            $this->pdo->exec("set names $charset");
        } catch (\PDOException $e) {
            throw new \Exception("PDO exception: {$e->getMessage()}");
        }
    }

    /** Начинает транзакцию. */
    public function transaction(): void
    {
        $this->pdo->beginTransaction();
    }

    /** Отменяет изменения транзакции. */
    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    /** Применяет изменения транзакции. */
    public function commit(): void
    {
        $this->pdo->commit();
    }

    /**
     * @brief Создание записи(-ей) в таблице.
     *
     * @param[in] $table - название таблицы.
     * @param[in] $values - значения для Insert::values().
     *
     * @return Объект класса Actions\Insert.
     */
    public function insert(string $table, array $values = []): Insert
    {
        $object = new Insert($this->pdo, $table);

        if ( !empty($values) )
            $object->values($values);

        return $object;
    }

    /**
     * @brief Получение записи(-ей) из таблицы.
     *
     * @param[in] $table - название таблицы.
     * @param[in] $columns - столбцы для Select::columns().
     *
     * @return Объект класса Actions\Select.
     */
    public function select(string $table, array $columns = []): Select
    {
        $object = new Select($this->pdo, $table);

        if ( !empty($columns) )
            $object->columns($columns);

        return $object;
    }

    /**
     * @brief Обновление записи(-ей) в таблице.
     *
     * @param[in] $table - название таблицы.
     * @param[in] $values - значения для Update::values().
     *
     * @return Объект класса Actions\Update.
     */
    public function update(string $table, array $values = []): Update
    {
        $object = new Update($this->pdo, $table);

        if ( !empty($values) )
            $object->set($values);

        return $object;
    }

    /**
     * @brief Удаление записи(-ей) из таблицы.
     *
     * @param[in] $table - название таблицы.
     *
     * @return Объект класса Actions\Delete.
     */
    public function delete(string $table): Delete
    {
        return new Delete($this->pdo, $table);
    }
}
