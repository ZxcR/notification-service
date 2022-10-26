<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";

$channel = $connection->channel();
$channel->queue_declare('manager', false, false, false, false);

$reciveCallback = function ($msg) {
    echo "Start supervisor\n";
    exec("/bin/sh /var/www/html/bash/runner.sh > /dev/null &");
    sleep(2);
    exec("php /var/www/html/console/worker_killer.php > /dev/null &");
    $msg->ack();
    echo "Runned supervisor";
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('manager', '', false, false, false, false, $reciveCallback);

while ($channel->is_open()) {
    $channel->wait();
}