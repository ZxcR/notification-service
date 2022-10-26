<?php

set_time_limit(300);
ini_set('memory_limit', '-1');

require_once '../index.php';
require_once '../config/db.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$sql = "SELECT * FROM users";
$stmt = $connect->prepare($sql);
//$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);




$connection = new AMQPStreamConnection(
    RABBIT_HOST,
    RABBIT_PORT,
    RABBIT_USER,
    RABBIT_PASS
);

$channel = $connection->channel();
$queue = "emails";

$channel->queue_declare($queue, false, false, false, false);

//$c = count($users);
$c = 10000;

for($i = 0; $i < $c; $i++) {
    $msg = new AMQPMessage($i . " " .$users[$i]["email"]);
    $channel->basic_publish($msg, '', $queue);
    //log published mails
}

$channel->queue_declare("manager", false, false, false, false);
$msg = new AMQPMessage("done {$c}");
$channel->basic_publish($msg, '', "manager");


$channel->close();
$connection->close();