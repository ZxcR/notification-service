<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";

$channel = $connection->channel();
$channel->queue_declare('check_mail', false, false, false, false);


$reciveCallback = function ($msg) {
    try {
        check_mail($msg->body);
        $msg->ack();
    } catch (Exception $e) {
        //log failed mail checking
    }

};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('check_mail', '', false, false, false, false, $reciveCallback);

while ($channel->is_open()) {
    $channel->wait();
}

