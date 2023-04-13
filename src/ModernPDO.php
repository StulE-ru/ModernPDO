<?php

namespace ModernPDO;

//
// Подключение пространств имен.
//

use ModernPDO\Actions\Delete;
use ModernPDO\Actions\Insert;
use ModernPDO\Actions\Select;
use ModernPDO\Actions\Update;

/**
 * @brief Класс-обертка над \PDO.
 */
final class ModernPDO
{
    private \PDO $pdo;

    /**
     * @brief Конструктор класса.
     *
     * @param string $type     - тип базы данных
     * @param string $charset  - используемая кодировка
     * @param string $host     - хост базы данных
     * @param string $username - имя пользователя базы данных
     * @param string $password - пароль пользователя базы данных
     * @param string $database - название базы данных
     * @param ?array $data     - массив параметров
     * @param ?\PDO  $pdo      - инициализированный объект \PDO
     *
     * @note На данный момент есть 3 варианта инициализации класса:
     *          1 - через параметры ($type, $charset, $host, ...).
     *          2 - через массив с параметрами (имена параметров должны совпадать с именами первого способа).
     *          3 - через инициализированный объект \PDO.
     *       Выбор способа зависит от переданных параметров в конструктор.
     *
     *       + если не указаны $type/$charset/$host, то будут взяты значения по умолчанию.
     */
    public function __construct(
        string $type = '',
        string $charset = '',
        string $host = '',
        string $username = '',
        string $password = '',
        string $database = '',

        ?array $data = null,

        ?\PDO $pdo = null,
    ) {
        if ($this->initByObject($pdo)) {
            return;
        }

        if ($this->initByArray($data)) {
            return;
        }

        $this->initByParams($type, $charset, $host, $username, $password, $database);
    }

    /**
     * @brief Инициализация через параметры.
     *
     * @param string $type     - тип базы данных
     * @param string $charset  - используемая кодировка
     * @param string $host     - хост базы данных
     * @param string $username - имя пользователя базы данных
     * @param string $password - пароль пользователя базы данных
     * @param string $database - название базы данных
     */
    private function initByParams(
        string $type = '',
        string $charset = '',
        string $host = '',
        string $username = '',
        string $password = '',
        string $database = '',
    ): void {
        if (empty($type)) {
            $type = 'mysql';
        }
        if (empty($charset)) {
            $charset = 'utf8mb4';
        }
        if (empty($host)) {
            $host = 'localhost';
        }

        $this->pdo = new \PDO("{$type}:host={$host};dbname={$database}", $username, $password);

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        $this->pdo->exec("set names {$charset}");
    }

    /**
     * @brief Инициализация через массив параметров.
     *
     * @param ?array $data - массив параметров
     *
     * @return bool в случае успеха true, иначе false
     */
    private function initByArray(?array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        call_user_func_array([$this, 'initByParams'], $data);

        return true;
    }

    /**
     * @brief Инициализация через объект \PDO.
     *
     * @param ?\PDO $pdo - инициализированный объект \PDO
     *
     * @return bool в случае успеха true, иначе false
     */
    private function initByObject(?\PDO $pdo): bool
    {
        if (empty($pdo)) {
            return false;
        }

        $this->pdo = $pdo;

        return true;
    }

    /**
     * @brief Инициализация транзакции.
     *
     * @return bool в случае успеха true, иначе false
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
     * @return bool в случае успеха true, иначе false
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
     * @return bool в случае успеха true, иначе false
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
     * @return bool если начата true, иначе false
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
     * @param ?string $name - имя объекта последовательности, который должен выдать ID
     *
     * @return string|false
     *                      Если объект последовательности для $name не задан, функция вернёт строку,
     *                      представляющую ID последней добавленной в базу записи.
     *                      Если же объект последовательности для $name задан, функция вернёт строку,
     *                      представляющую последнее значение, полученное от этого объекта.
     *
     * @note Обертка PDO::lastInsertId.
     */
    public function getLastId(?string $name = null): string|false
    {
        return $this->pdo->lastInsertId($name);
    }

    /**
     * @brief Выполнение "сырых" SQL-запросов.
     *
     * @param string $query - SQL-выражение, которое надо выполнить
     *
     * @return int|false в случае успеха кол-во затронутых записей, иначе false
     *
     * @note Обертка PDO::exec.
     */
    public function exec(string $query): int|false
    {
        return $this->pdo->exec($query);
    }

    /**
     * @brief Выполнение "готовых" SQL-запросов.
     *
     * @param string $query  - SQL-выражение, которое надо выполнить
     * @param array  $values - массив значений, которые будут подставлены вместо всех '?'
     *
     * @return Statement объект класса Statement
     */
    public function query(string $query, array $values = []): Statement
    {
        if (empty($values)) {
            $statement = $this->pdo->query($query);
        } else {
            $statement = $this->pdo->prepare($query);
            $statement?->execute($values);
        }

        if (!is_object($statement)) {
            $statement = null;
        }

        return new Statement($statement);
    }

    /**
     * @brief Создание записи(-ей) в таблице.
     *
     * @param string $table  - название таблицы
     * @param array  $values - значения для Insert::values()
     *
     * @return Insert объект класса Actions\Insert
     */
    public function insert(string $table, array $values = []): Insert
    {
        $object = new Insert($this->pdo, $table);

        if (!empty($values)) {
            $object->values($values);
        }

        return $object;
    }

    /**
     * @brief Получение записи(-ей) из таблицы.
     *
     * @param string $table   - название таблицы
     * @param array  $columns - столбцы для Select::columns()
     *
     * @return Select объект класса Actions\Select
     */
    public function select(string $table, array $columns = []): Select
    {
        $object = new Select($this->pdo, $table);

        if (!empty($columns)) {
            $object->columns($columns);
        }

        return $object;
    }

    /**
     * @brief Обновление записи(-ей) в таблице.
     *
     * @param string $table  - название таблицы
     * @param array  $values - значения для Update::values()
     *
     * @return Update объект класса Actions\Update
     */
    public function update(string $table, array $values = []): Update
    {
        $object = new Update($this->pdo, $table);

        if (!empty($values)) {
            $object->set($values);
        }

        return $object;
    }

    /**
     * @brief Удаление записи(-ей) из таблицы.
     *
     * @param string $table - название таблицы
     *
     * @return Delete объект класса Actions\Delete
     */
    public function delete(string $table): Delete
    {
        return new Delete($this->pdo, $table);
    }
}
