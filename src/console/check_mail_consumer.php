<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;

$opt = getopt(null, ["name:"]);

$connection = new AMQPStreamConnection(
    RABBIT_HOST,
    RABBIT_PORT,
    RABBIT_USER,
    RABBIT_PASS
);

$channel = $connection->channel();
$channel->queue_declare('check_mail', false, false, false, false);


$reciveCallback = function ($msg) use($opt) {
    check_mail($msg->body);
    $msg->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('check_mail', '', false, false, false, false, $reciveCallback);

while ($channel->is_open()) {
    $channel->wait();
}

