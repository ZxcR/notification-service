<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";
require_once $_SERVER["DOCUMENT_ROOT"] . './config/db.php';


// sql to create table
$sql = "CREATE TABLE `users` (
	`id` BIGINT(19) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_general_ci',
	`email` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`validts` TIMESTAMP NOT NULL,
	`confirmed` TINYINT(1) NOT NULL DEFAULT '0',
	`checked_email` TINYINT(1) NOT NULL DEFAULT '0',
	`valid_email` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `Индекс 3` (`validts`, `confirmed`, `valid_email`) USING BTREE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB";

if ($connect->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $connect->error;
}

$connect->close();