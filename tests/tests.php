<?php

$config = require_once "private.php";

require_once "../src/ModernPDO.php";

$modernPDO = new \ModernPDO\ModernPDO(
    type: $config["type"],
    charset: $config["charset"],
    host: $config["host"],
    username: $config["username"],
    password: $config["password"],
    database: $config["database"],
);

echo "<pre>";

echo "\n\nInsert test\n";

$status = $modernPDO->insert("account")->values(["email" => "test@test.test", "name" => "StulE", "password" => "123123123"])->execute();

var_dump($status);

echo "\n\nSelect test\n";

$account = $modernPDO->select("account")->columns(["id", "name"])->where("email", "test@test.test")->and("name", "StulE")->one();

var_dump($account);

echo "\n\nUpdate test\n";

$status = $modernPDO->update("account")->where("id", $account["id"])->set(["password" => "updated"])->execute();

var_dump($status);

echo "\n\nDelete test\n";

$status = $modernPDO->delete("account")->where("id", $account["id"])->execute();

var_dump($status);

echo "</pre>";
