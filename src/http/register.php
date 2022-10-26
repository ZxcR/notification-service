<?php
require_once $_SERVER["DOCUMENT_ROOT"] . '/index.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/config/db.php';

use PhpAmqpLib\Message\AMQPMessage;

$faker = Faker\Factory::create();

$stmt = $connect->prepare("INSERT INTO users SET username = ?, email = ?, validts = ?, confirmed = 0, checked_email = 0, valid_email = 0");
$time = date("Y-m-d H:i:s");

$stmt->bind_param("sss", $faker->name(), $faker->email(), $time);
$stmt->execute();
$connect->close();


try {
    $channel = $connection->channel();
    $queue = "check_mail";

    $msg_body = $faker->email();

    $channel->queue_declare($queue, false, false, false, false);

    $msg = new AMQPMessage($msg_body);
    $channel->basic_publish($msg, '', $queue);
    $channel->close();
    $connection->close();
} catch (Exception $e) {
    //log failed check mail job
}