<?php

namespace ModernPDO;

use ModernPDO\Actions\Select;

/**
 * Wrapper over PDO.
 *
 * Use ModerPDO::create... methods for create connections.
 */
class ModernPDO
{
    /** Default port for MySQL. */
    public const MYSQL_DEFAULT_PORT = '3306';
    /** Default port for MariaDB. */
    public const MARIADB_DEFAULT_PORT = '3306';
    /** Default port for PostgreSQL. */
    public const POSTGRESQL_DEFAULT_PORT = '5432';

    private function __construct(
        private \PDO $pdo,
    ) {
    }

    /**
     * Creates and returns ModernPDO object using PDO object.
     */
    public static function createByPDO(\PDO $pdo): ModernPDO
    {
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

        return new ModernPDO($pdo);
    }

    /**
     * Returns ModernPDO object configured with MySQL.
     *
     * @param string $host The hostname on which the database server reside
     * @param string $database
     * @param string $username The user name for the DSN string
     * @param string $password The password for the DSN string
     * @param string $charset Connection charset, if empty, default database charset is used
     * @param string $port Connection port, if empty, default is used
     *
     * @see https://www.php.net/manual/ru/ref.pdo-mysql.php
     */
    public static function createMySQL(
        string $host,
        string $database,
        string $username,
        string $password,
        string $charset = '',
        string $port = '',
    ): ModernPDO {
        if (empty($port)) {
            $port = self::MYSQL_DEFAULT_PORT;
        }

        $pdo = new \PDO(
            'mysql:host=' . $host . ';dbname=' . $database . ';port=' . $port,
            $username,
            $password,
        );

        if (!empty($charset)) {
            $pdo->exec('SET NAMES ' . $charset);
        }

        return self::createByPDO($pdo);
    }

    /**
     * Returns ModernPDO object configured with MariaDB.
     *
     * @param string $host The hostname on which the database server reside
     * @param string $database The name of the database
     * @param string $username The user name for the DSN string
     * @param string $password The password for the DSN string
     * @param string $charset Connection charset, if empty, default database charset is used
     * @param string $port Connection port, if empty, default is used
     *
     * @see https://www.php.net/manual/ru/ref.pdo-mysql.php
     */
    public static function createMariaDB(
        string $host,
        string $database,
        string $username,
        string $password,
        string $charset = '',
        string $port = '',
    ): ModernPDO {
        if (empty($port)) {
            $port = self::POSTGRESQL_DEFAULT_PORT;
        }

        return self::createMySQL(
            host: $host,
            database: $database,
            username: $username,
            password: $password,
            charset: $charset,
            port: $port,
        );
    }

    /**
     * Returns ModernPDO object configured with PostgreSQL.
     *
     * @param string $host The hostname on which the database server reside
     * @param string $database The name of the database
     * @param string $username The user name for the DSN string
     * @param string $password The password for the DSN string
     * @param string $port Connection port, if empty, default is used
     *
     * @see https://www.php.net/manual/ru/ref.pdo-pgsql.php
     */
    public static function createPostgreSQL(
        string $host,
        string $database,
        string $username,
        string $password,
        string $port = '',
    ): ModernPDO {
        if (empty($port)) {
            $port = self::POSTGRESQL_DEFAULT_PORT;
        }

        return self::createByPDO(
            new \PDO(
                'pgsql:host=' . $host . ';dbname=' . $database . ';port=' . $port,
                $username,
                $password,
            ),
        );
    }

    /**
     * Returns ModernPDO object configured with SQLite3.
     *
     * @param string $mode Full path to the database file or :memory: or an empty string
     *
     * - To access a database on disk, $mode must be full path to the database file.
     * - To create a database in memory, $mode must be ':memory:'.
     * - To use a temporary database, which is deleted when the connection is closed, $mode must be an empty string.
     *
     * @see https://www.php.net/manual/en/ref.pdo-sqlite.connection.php
     */
    public static function createSQLite3(
        string $mode = '',
    ): ModernPDO {
        return self::createByPDO(
            new \PDO('sqlite:' . $mode),
        );
    }

    /**
     * Returns Transaction object.
     */
    public function transaction(): Transaction
    {
        return new Transaction($this->pdo);
    }

    /**
     * Execute an SQL statement and return the number of affected rows.
     *
     * @param string $query The SQL statement to execute
     *
     * @see https://www.php.net/manual/en/pdo.exec.php
     */
    public function exec(string $query): int
    {
        $count = $this->pdo->exec($query);

        return $count !== false ? $count : 0;
    }

    /**
     * Prepares and executes an SQL statement.
     *
     * @param string $query The SQL statement to prepare and execute
     * @param array $values Placeholders that will replace '?'
     *
     * @see https://www.php.net/manual/en/pdo.query.php
     * @see https://www.php.net/manual/en/pdo.prepare.php
     * @see https://www.php.net/manual/en/pdostatement.execute.php
     */
    public function query(string $query, array $values = []): Statement
    {
        if (empty($values)) {
            $statement = $this->pdo->query($query);
        } else {
            $statement = $this->pdo->prepare($query);

            if ($statement !== false) {
                $statement->execute($values);
            }
        }

        return new Statement($statement !== false ? $statement : null);
    }

    public function select(string $table): Select
    {
        return new Select($this, $table);
    }
}
