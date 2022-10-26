<?php
require_once dirname(__DIR__) . '/../src/index.php';
require_once dirname(__DIR__) . '/../src/config/db.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$faker = Faker\Factory::create();

$stmt = $connect->prepare("INSERT INTO users SET username = ?, email = ?, validts = ?, confirmed = 1, checked_email = 1, valid_email = 1");
$time = "2022-10-26 23:59:59";
$stmt->bind_param("sss", $faker->name(), $faker->email(), $time);
$stmt->execute();
$connect->close();


try {
    $connection = new AMQPStreamConnection(
        RABBIT_HOST,
        RABBIT_PORT,
        RABBIT_USER,
        RABBIT_PASS
    );

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