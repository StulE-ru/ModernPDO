<h1 align="center">stule-ru/modernpdo</h1>

<div align="center">

![Downloads](https://img.shields.io/packagist/dt/stule-ru/modernpdo
)
![Release](https://img.shields.io/github/v/release/StulE-ru/ModernPDO)
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

1. Add a require `"stule-ru/modernpdo": "^2.0.0"` to your composer.json.

```
...
   "require": {
        ...
        "stule-ru/modernpdo": "^2.0.0"
        ...
   }
...
```

2. Run the command `composer update` in the console.

## 🎈 Usage <a name = "usage"></a>

- [Initialization](#usage_initialization)
- [Queries](#usage_queries)
- [CRUD](#usage_crud)
- [Transaction](#usage_transaction)

### Initialization Examples <a name = "usage_initialization"></a>

```php
use ModernPDO\ModernPDO;
use ModernPDO\Drivers\MySQLDriver;
use ModernPDO\Drivers\MariaDBDriver;
use ModernPDO\Drivers\PostgreSQLDriver;
use ModernPDO\Drivers\SQLite3Driver;

// Initiolize MySQL
$mpdo = new MySQLDriver(
    host: getenv('MYSQL_HOST'),
    database: getenv('MYSQL_DATABASE'),
    username: getenv('MYSQL_USERNAME'),
    password: getenv('MYSQL_PASSWORD'),
    charset: getenv('MYSQL_CHARSET'),
    // port: getenv('MYSQL_PORT'),
);

// Initiolize MariaDB
$mpdo = new MariaDBDriver(
    host: getenv('MARIADB_HOST'),
    database: getenv('MARIADB_DATABASE'),
    username: getenv('MARIADB_USERNAME'),
    password: getenv('MARIADB_PASSWORD'),
    charset: getenv('MARIADB_CHARSET'),
    // port: getenv('MARIADB_PORT'),
);

// Initiolize PostgreSQL
$mpdo = new PostgreSQLDriver(
    host: getenv('POSTGRES_HOST'),
    database: getenv('POSTGRES_DATABASE'),
    username: getenv('POSTGRES_USERNAME'),
    password: getenv('POSTGRES_PASSWORD'),
    // port: getenv('POSTGRES_PORT'),
);

// Initiolize SQLite3
$mpdo = new SQLite3Driver(
    mode: getenv('SQLITE3_MODE'),
);

// Initiolize by PDO
$mpdo = new ModernPDO(
    pdo: $your_pdo_object,
);
```

### Queries Examples <a name = "usage_queries"></a>

```php
// Row query

$mpdo->exec('CREATE TABLE table_name (id int, name varchar(32));');

// Prepared queries

$modernPDO->query("SELECT * FROM table_name WHERE name=?", [$name])->fetchAll();
$modernPDO->query("SELECT * FROM table_name WHERE id=?", [$id])->fetch();
```

### CRUD Examples <a name = "usage_crud"></a>

```php
// Insert example

$mpdo->insert('table_name')->values(['id' => 10, 'name' => 'test'])->execute();

// Select examples

$mpdo->select('table_name')->all();

$mpdo->select('table_name')->where('id', 10)->one();
$mpdo->select('table_name')->where('id', 10)->and('name' => 'test')->one();

// Update example

$mpdo->update('table_name')->set(['name' => 'Mr. Gorski'])->where('id', 10)->execute();

// Delete example

$mpdo->delete('table_name')->where('id', 10)->execute();
```

### Transaction Example <a name = "usage_transaction"></a>

```php
try {
    $transaction = $mpdo->transaction();

    $transaction->begin();

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

> **_NOTE:_** If you want to start integration tests you must <br>
1 - install [docker/docker-compose](https://www.docker.com/get-started/) <br>
2 - build php image using `docker build --file ./.github/docker/php/Dockerfile --tag php8.1 --build-arg PHP_VERSION=8.1 .` <br>
3 - start docker container using `docker compose up -d`

### Coverage/Levels

- **Integration tests**
> Code Coverage Report (2023-08-17 12:46:09) <br>
Summary: <br>
\- Classes: 100.00% (12/12) <br>
\- Methods: 100.00% (48/48) <br>
\- Lines:   100.00% (139/139)

- **Unit tests**
> Code Coverage Report (2023-08-17 12:46:43) <br>
Summary: <br>
\- Classes: 91.67% (11/12) <br>
\- Methods: 72.92% (35/48) <br>
\- Lines:   69.06% (96/139)

- PHPStan
> Level: 9 <br>
Erros: 0

- PSalm
> Level: 1 <br>
Erros: 0

### Composer scripts <a name = "tests_composer_scripts"></a>

#### Tests
- `composer tests` - runs all PHPUnit tests (run in docker)
- `composer tests-ca` - defines test coverage (run in docker)

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
