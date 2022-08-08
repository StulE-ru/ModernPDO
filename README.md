# ModernPDO

ModernPDO is a simple library for PHP v8.1

## Getting Started

Download the latest release, create the directory `ModernPDO` in your library directory, and drop `ModernPDO/src/` into `[lib-dir]/ModernPDO/`.

Create the new ModernPDO instance:

```php
$modernPDO = new \ModernPDO\ModernPDO(
        $type, // (mysql, etc.)
        $charset, // (utf8, utf8mb4, etc.)
        $host, // (localhost, etc.)
        $username, // user's name
        $password, // user's password
        $database, // database's name
);
```

## CRUD Examples

##### DELETE

```php
$modernPDO->delete($table)->where($column, $value)->execute();
```

##### INSERT

```php
$modernPDO->insert($table)->values([$column1 => $value1, etc.])->execute();
```

##### SELECT

```php
// get all rows
$modernPDO->select($table)->all();
// get rows where $column == $value
$modernPDO->select($table)->where($column, $value)->one();
// get first row by $order where $column == $value
$modernPDO->select($table)->where($column, $value)->firstBy($order)
// get last row by $order where $column == $value
$modernPDO->select($table)->where($column, $value)->lastBy($order)
```

##### UPDATE

```php
$modernPDO->update($table)->set([$column1 => $value1, etc.])->execute();
```

## Good Luck and Have Fun 💩
