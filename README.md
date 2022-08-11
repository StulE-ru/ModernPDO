<h1 align="center"> ModernPDO </h1>

<h4 align="center">

![Release](https://img.shields.io/github/v/release/StulE-ru/ModernPDO)
![Downloads](https://img.shields.io/github/downloads/StulE-ru/ModernPDO/total)
![Watchers](https://img.shields.io/github/watchers/StulE-ru/ModernPDO)
![Forks](https://img.shields.io/github/forks/StulE-ru/ModernPDO)
![Stars](https://img.shields.io/github/stars/StulE-ru/ModernPDO)
![Made by](https://img.shields.io/badge/made%20by-StulE--ru-blue)
![License](https://img.shields.io/github/license/StulE-ru/ModernPDO)

</h4>

<h2 align="center"> Getting Started </h2>

Download the latest release, create the directory `ModernPDO` in your library directory, and drop `ModernPDO/src/` into `[lib-dir]/ModernPDO/`.

Create the new ModernPDO instance:

```php
// NOTE:
// If $type/$charset/$host are empty, default values will be used.
// For $type - "mysql"
// For $charset - "utf8mb4"
// For $host - "localhost"

//
// From params
//

$modernPDO = new \ModernPDO\ModernPDO(
        type: $type, // (mysql, etc.)
        charset: $charset, // (utf8, utf8mb4, etc.)
        host: $host, // (localhost, etc.)
        username: $username, // user's name
        password: $password, // user's password
        database: $database, // database's name
);

//
// From array
//

$config = [
    "type" => "",  // (mysql, etc.)
    "charset" => "",  // (utf8, utf8mb4, etc.)
    "host" => "", // (localhost, etc.)
    "username" => "", // user's name
    "password" => "", // user's password
    "database" => "", // database's name
];

$modernPDO = new \ModernPDO\ModernPDO(data: $config);

//
// From PDO object
//

$pdo = new PDO(...);

$modernPDO = new \ModernPDO\ModernPDO(pdo: $pdo);
```

<h3 align="center"> Queries Examples </h3>

#### Source queries

```php
// get all accounts
$modernPDO->exec("SELECT * FROM `account` WHERE 1");
```

#### Prepared queries

```php
// get all accounts where balance >= ? 
$accounts = $modernPDO->query("SELECT * FROM `account` WHERE `balance` >= ?", [1000])->fetchAll();
// get one account where name == ?
$account = $modernPDO->query("SELECT * FROM `account` WHERE `name` = ?", ["StulE"])->fetch();
```

<h3 align="center"> CRUD Examples </h3>

#### INSERT

```php
// long syntax
$modernPDO->insert($table)->values([$col1 => $val1, ...])->execute();
// short syntax
$modernPDO->insert($table, [$col1 => $val1, ...])->execute();
```

#### SELECT

```php
// get all rows from $table
$modernPDO->select($table)->all();
// get rows from $table where $col == $val
$modernPDO->select($table)->where($col, $val)->one();
// get first row from $table by $order where $col == $val
$modernPDO->select($table)->where($col, $val)->firstBy($order);
// get last row from $table by $order where $col == $val
$modernPDO->select($table)->where($col, $val)->lastBy($order);

// long syntax
$modernPDO->select($table)->columns([$col1, $col2, ...])->all();
// short syntax
$modernPDO->select($table, [$col1, $col2, ...])->all();
```

#### UPDATE

```php
// long syntax
$modernPDO->update($table)->set([$col1 => $val1, ...])->where($col, $val)->execute();
// short syntax
$modernPDO->update($table, [$col1 => $val1, ...])->where($col, $val)->execute();
```

#### DELETE

```php
$modernPDO->delete($table)->where($col, $val)->execute();
```

<h2 align="center"> 😘 Good Luck and Have Fun 😘 </h2>
