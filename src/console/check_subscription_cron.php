<?php

set_time_limit(300);
ini_set('memory_limit', '-1');

require_once '../index.php';
require_once '../config/db.php';

use PhpAmqpLib\Message\AMQPMessage;

$date = new Date("Y-m-d H:i:s", strtotime(' + 3 days'));

$sql = "SELECT id, emails FROM users Where validts < ? AND confirmed = 1 AND valid = 1";
$stmt = $connect->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);


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