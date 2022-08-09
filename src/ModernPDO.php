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
     * @param string $charset - используемая кодировка.
     * @param string $charset - используемая кодировка.
     * @param string $host - хост базы данных.
     * @param string $username - имя пользователя базы данных.
     * @param string $password - пароль пользователя базы данных.
     * @param string $database - название базы данных.
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

    /**
     * @brief Инициализация транзакции.
     *
     * @return bool В случае успеха true, иначе false.
     *
     * @note Обертка PDO::beginTransaction.
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * @brief Откат транзакции.
     *
     * @return bool В случае успеха true, иначе false.
     *
     * @note Обертка PDO::rollBack.
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * @brief Фиксация транзакцию.
     *
     * @return bool В случае успеха true, иначе false.
     *
     * @note Обертка PDO::commit.
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * @brief Начата ли транзакция?
     *
     * @return bool Если начата true, иначе false.
     *
     * @note Обертка PDO::inTransaction.
     */
    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    /**
     * @brief Получение ID последней вставленной строки или значение последовательност.
     *
     * @param ?string $name - имя объекта последовательности, который должен выдать ID.
     *
     * @return string|false
     *          Если объект последовательности для $name не задан, функция вернёт строку,
     *          представляющую ID последней добавленной в базу записи.
     *          Если же объект последовательности для $name задан, функция вернёт строку,
     *          представляющую последнее значение, полученное от этого объекта.
     *
     * @note Обертка PDO::lastInsertId.
     */
    public function getLastId(?string $name = null): string|false
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * @brief Создание записи(-ей) в таблице.
     *
     * @param string $table - название таблицы.
     * @param array $values - значения для Insert::values().
     *
     * @return Insert Объект класса Actions\Insert.
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
     * @param string $table - название таблицы.
     * @param array $columns - столбцы для Select::columns().
     *
     * @return Select Объект класса Actions\Select.
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
     * @param string $table - название таблицы.
     * @param array $values - значения для Update::values().
     *
     * @return Update Объект класса Actions\Update.
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
     * @param string $table - название таблицы.
     *
     * @return Delete Объект класса Actions\Delete.
     */
    public function delete(string $table): Delete
    {
        return new Delete($this->pdo, $table);
    }
}
