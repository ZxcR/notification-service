<?php

define('HOST', 'mysql_test');
define('USER', 'root');
define('PASSWORD', 'root');
define('DATABASE', 'notifications');
define('DB_PORT', 3306);

$connect = mysqli_connect(HOST, USER, PASSWORD, DATABASE, DB_PORT);

if (!$connect) {
    die('Error connect to database!');
}