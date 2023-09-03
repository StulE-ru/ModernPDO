<h1 align="center">stule-ru/modernpdo</h1>

<div align="center">

![Downloads](https://img.shields.io/packagist/dt/stule-ru/modernpdo
)
![Release](https://img.shields.io/github/v/release/StulE-ru/ModernPDO)
![Code Coverage Badge](./.github/badge.svg)
![Forks](https://img.shields.io/github/forks/StulE-ru/ModernPDO)
![Stars](https://img.shields.io/github/stars/StulE-ru/ModernPDO)
![License](https://img.shields.io/github/license/StulE-ru/ModernPDO)

</div>

<p align="center">
This repository contains code of ModernPDO, the library for working with databases using PDO. <br>
The library is guaranteed to support the following databases: <a href="https://www.mysql.com/">MySQL</a>, <a href="https://mariadb.org/">MariaDB</a>, <a href="https://www.postgresql.org/">PostgreSQL</a>, <a href="https://www.sqlite.org/index.html">SQLite3</a> and custom PDO.
</p>

## 📝 Table of Contents

- [About](#about)
- [Getting Started](#getting_started)
- [Usage](#usage)
- [Tests](#tests)
- [Built Using](#built_using)
- [Authors](#authors)

## 🧐 About <a name = "about"></a>

This repository contains code of ModernPDO, a library for working with databases using PDO. There are basic tools for working with databases. <br>
For example, here you can see classes for working with insert, delete, select etc. <br>
You can use the library instead of PDO which makes project development easier.

## 🏁 Getting Started <a name = "getting_started"></a>

### Installing

The library uses composer autoloader for including files, so you need to install [Composer](https://getcomposer.org/). If you've never used Composer read [the manual](https://getcomposer.org/doc/00-intro.md). You can install via command line or composer.json.

#### Using command line

1. Run the command `composer require stule-ru/modernpdo` in the console.

#### Using composer.json

1. Add a require `"stule-ru/modernpdo": "^3.0.0"` to your composer.json.

```
...
   "require": {
        ...
        "stule-ru/modernpdo": "^3.0.0"
        ...
   }
...
```

2. Run the command `composer install` in the console.

## 🎈 Usage <a name = "usage"></a>

- [Structure](#usage_structure)
- [Initialization](#usage_initialization)
- [Queries](#usage_queries)
- [CRUD](#usage_crud)
- [Table](#usage_table)
- [Transaction](#usage_transaction)

### About Structure <a name = "usage_structure"></a>

> **_NOTE:_** <br>
>If you want to just use the library and not think about the implementation, [install](getting_started) and [use](usage_initialization) <br>
> Everyone else, welcome to hell =)

#### About Source Code

The source code contains:
- The general classes ([ModernPDO.php](/src/ModernPDO.php), [Factory.php](/src/Factory.php), [Escaper.php](/src/Escaper.php), etc.)
- The specific DBMS classes (they are located in [/src/Drivers/\*](/src/Drivers/))

This allows **append** code rather than **rewrite** ([MySQLDriver](/src/Drivers/MySQLDriver.php), [PostgreSQLDriver](/src/Drivers/PostgreSQLDriver.php), [SQLite3Driver](/src/Drivers/SQLite3Driver.php) extend [ModernPDO](/src/ModernPDO.php) and etc.) <br>

So, if you need to, you can extend all general classes to achieve your goals.

The general classes:
- [**Actions/\***](/src/Actions) - query builders like [Select](/src/Actions/Select.php), [Update](/src/Actions/Update.php) and etc.
- [**Conditions/\***](/src/Conditions) - condition builders for 'select' like [Between](/src/Conditions/Between.php), [In](/src/Conditions/In.php) and etc.
- [**Fields/\***](/src/Fields) - field builders for 'create/update table' like [IntField](/src/Fields/IntField.php), [TextField](/src/Fields/TextField.php) and etc.
- [**Functions/\***](/src/Functions) - aggregate and scalar function builders like [Count](/src/Functions/Aggregate/Count.php), [Upper](/src/Functions/Scalar/String/Upper.php) and etc.
- [**Keys/\***](/src/Keys) - key builders for 'create/update table' like [PrimaryKey](/src/Keys/PrimaryKey.php), [UniqueKey](/src/Keys/UniqueKey.php) and etc.
- [**Traits\***](/src/Traits) - traits with shared methods like where(), columns() and etc.
- [**ModernPDO**](/src/ModernPDO.php) - base class for working with database (methods exec(), query(), select() and etc.)
- [**Escaper**](/src/Escaper.php) - base class for escaping values.
- [**Factory**](/src/Factory.php) - base class for making new actions, transactions and etc.
- [**Statement**](/src/Statement.php) - base class for working with database response.
- [**Transaction**](/src/Transaction.php) - base class for working with transactions.

The drivers:
- [MariaDB](/src/Drivers/MariaDBDriver.php) - driver for working with [MariaDB](https://mariadb.org/).
- [MySQL](/src/Drivers/MySQLDriver.php) - driver for working with [MySQL](https://www.mysql.com/).
- [PostgreSQL](/src/Drivers/PostgreSQLDriver.php) - driver for working with [PostgreSQL](https://www.postgresql.org/).
- [SQLite3](/src/Drivers/SQLite3Driver.php) - driver for working with [SQLite3](https://www.sqlite.org/index.html).

#### About Tests

There are 2 types of tests: [**Integration**](/tests/Integration) and [**Unit**](/tests/Unit).

### Initialization Examples <a name = "usage_initialization"></a>

```php
use ModernPDO\ModernPDO;
use ModernPDO\Drivers\MySQLDriver;
use ModernPDO\Drivers\MariaDBDriver;
use ModernPDO\Drivers\PostgreSQLDriver;
use ModernPDO\Drivers\SQLite3Driver;

// Initiolize by PDO
$mpdo = new ModernPDO(
    pdo: $pdo,
);

// Initiolize MySQL
$mpdo = new MySQLDriver(
    host: $host,
    database: $database,
    username: $username,
    password: $password,
    charset: $charset,
    //port: $port,
);

// Initiolize MariaDB
$mpdo = new MariaDBDriver(
    host: $host,
    database: $database,
    username: $username,
    password: $password,
    charset: $charset,
    //port: $port,
);

// Initiolize PostgreSQL
$mpdo = new PostgreSQLDriver(
    host: $host,
    database: $database,
    username: $username,
    password: $password,
    //port: $port,
);

// Initiolize SQLite3
$mpdo = new SQLite3Driver(
    mode: $mode,
);
```

### Queries Examples <a name = "usage_queries"></a>

```php
// Row query

$mpdo->exec('CREATE TABLE table_name (id int, name varchar(32));');

// Prepared queries

$stmt = $mpdo->query("SELECT * FROM table_name", []);

// Check query status
if ($stmt->status()) {

    // Get counts
    $stmt->rowCount();
    $stmt->columnCount();

    $stmt->fetchColumn($column); // Fetch cell
    $stmt->fetchObject(); // Fetch row as object
    $stmt->fetch(); // Fetch row as array
    $stmt->fetchAll(); // Fetch all rows as array
}
```

### CRUD Examples <a name = "usage_crud"></a>

```php
//
// Insert example
//

// INSERT INTO table (id, name) VALUES (10, 'test'), (11, 'test')
$mpdo->insert('table')->columns([
    'id', 'name',
])->values([
    [10, 'test'],
    [11, 'test'],
])->execute();

// INSERT INTO table VALUES (12, 'test')
$mpdo->insert('table')->values([
    [12, 'test'],
])->execute();

//
// Select examples
//

// SELECT * FROM table
$mpdo->select('table')->rows();

// SELECT * FROM table WHERE id=10 LIMIT 1
$mpdo->select('table')->where('id', 10)->row();
// SELECT * FROM table WHERE id=10 AND name='test' LIMIT 1
$mpdo->select('table')->where('id', 10)->and('name', 'test')->row();

// SELECT id, name FROM table
$mpdo->select('table')->columns(['id', 'name'])->rows();

// SELECT COUNT(*) FROM table
$mpdo->select('table')->columns([new Count()])->cell();

// SELECT SUM(amount) FROM table WHERE id BETWEEN 10 AND 50
$mpdo->select('table')->columns([new Sum('amount')])->where('id', new Between(10, 50))->cell();

// SELECT table.id AS id, table.name AS name, join_table.lastname AS lastname FROM table INNER JOIN join_table ON table.id=join_table.id
$mpdo->select('table')->columns([
    'id' => 'table.id',
    'name' => 'table.name',
    'lastname' => 'join_table.lastname',
])->innerJoin('join_table')->on('table.id', 'join_table.id')->rows();

// SELECT * FROM table ORDER BY id ASC
$mpdo->select('table')->orderBy('id')->rows();

// SELECT * FROM table LIMIT 1 OFFSET 10
$mpdo->select('table')->limit(1, 10)->row();

//
// Update example
//

// UPDATE table SET name='Mr. Gorski' WHERE id=10
$mpdo->update('table')->set(['name' => 'Mr. Gorski'])->where('id', 10)->execute();

//
// Delete example
//

// DELETE FROM table WHERE id NOT IN (10, 11, 20)
$mpdo->delete('table')->where('id', new NotIn([10, 11, 20]))->execute();
```

### Table Examples <a name = "usage_table"></a>

```php
//
// Create Table
//

// CREATE TABLE IF NOT EXISTS table (id INT NOT NULL, email TEXT NOT NULL, name VARCHAR(32) NOT NULL)
$mpdo->createTable('table')->checkIfExists()->fields([
    new IntField('id'),
    new TextField('email'),
    new VarcharField('name', 32),
])->execute();

// CREATE TABLE IF NOT EXISTS table (id INT UNSIGNED NULL DEFAULT 100)
$mpdo->createTable('table')->checkIfExists()->fields([
    new IntField('id', unsigned: true, canBeNull: true, default: 100),
])->execute();

// CREATE TABLE IF NOT EXISTS table (id INT NOT NULL, PRIMARY KEY (id))
$mpdo->createTable('table')->checkIfExists()->fields([
    new IntField('id'),
])->keys([
    new PrimaryKey('id'),
])->execute();

//
// Update Table
//

// ALTER TABLE table RENAME TO new_table
$mpdo->alterTable('table')->rename('new_table')->execute();

// ALTER TABLE table ADD COLUMN amount INT NOT NULL
$mpdo->alterTable('table')->addColumns([
    new IntField('amount'),
])->execute();

// ALTER TABLE table RENAME COLUMN column TO new_column
$mpdo->alterTable('table')->renameColumns([
    'column' => 'new_column',
])->execute();

// ALTER TABLE table DROP COLUMN column
$mpdo->alterTable('table')->dropColumns([
    'column',
])->execute();

//
// Drop Table
//

// DROP TABLE IF EXISTS table
$mpdo->dropTable('table')->checkIfExists()->execute();
```

### Transaction Example <a name = "usage_transaction"></a>

```php
$transaction = $mpdo->transaction();

$transaction->begin();

try {
    if (!$transaction->isActive()) {
        // Your code...
    }

    // Your code...

    $transaction->commit();
} catch (\Throwable $ex) {
    $transaction->rollBack();

    throw $ex;
}
```

## 🔧 Running the tests <a name = "tests"></a>

There are many tools for testing: Integration/Unit tests (PHPUnit), PHPStan, PSalm and CSFixer. <br>
If you want to start them you need to run [composer scripts](#tests_composer_scripts) in terminal <br>

> **_NOTE:_** You can not start integration tests

### Levels

- PHPStan: Level 9
- PSalm: Level 1

### Composer scripts <a name = "tests_composer_scripts"></a>

#### Tests
- `composer tests` - runs all PHPUnit tests (do not run it)
- `composer i-tests` - runs integration PHPUnit tests (do not run it)
- `composer u-tests` - runs unit PHPUnit tests
- `composer ca-tests` - defines all tests coverage (do not run it)
- `composer ica-tests` - defines integration tests coverage (do not run it)
- `composer uca-tests` - defines unit tests coverage

#### PHPStan
- `composer stan` - writes errors to phpstan-report.xml
- `composer stan-bl` - writes errors to phpstan-baseline.neon

#### PSalm
- `composer salm` - writes errors to psalm-report.xml
- `composer salm-bl` - writes errors to psalm-baseline.xml

#### Coding standards fixer
- `composer csfix` - fixes coding standards in all PHP files

## ⛏️ Built Using <a name = "built_using"></a>

#### Languages

- [PHP](https://www.php.net/) - version >= 8.1

#### Utilities

- [PHPStan](https://github.com/phpstan/phpstan) - static analyzer
- [PSalm](https://github.com/vimeo/psalm) - static analyzer
- [PHPUnit](https://github.com/sebastianbergmann/phpunit) - testing framework
- [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) - coding standards fixer


## ✍️ Authors <a name = "authors"></a>

- [@StulE-ru](https://github.com/StulE-ru) - Developer
- [@deff-dev](https://github.com/deff-dev) - DevOps
