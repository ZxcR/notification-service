<?php
set_time_limit(300);
require_once "../index.php";
require_once '../config/db.php';


$faker = Faker\Factory::create();

$stmt = $connect->prepare("INSERT INTO users SET username = ?, email = ?, validts = ?, confirmed = 1, checked_email = 1, valid_email = 1");
$connect->begin_transaction();
for($i = 0; $i < 1000000; $i++) {
    $time = "2022-10-28 23:59:59";
    $stmt->bind_param("sss", $faker->name(), $faker->email(), $time);
    $stmt->execute();
}
$connect->commit();
