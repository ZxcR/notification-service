<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";
//require_once "/var/www/html/index.php";

//file_put_contents("/var/www/html/log/consumer_runned.log", $argv[1] . " " . now()."\n", FILE_APPEND);
use PhpAmqpLib\Connection\AMQPStreamConnection;
$opt = getopt(null, ["name:"]);

$connection = new AMQPStreamConnection(
    RABBIT_HOST,
    RABBIT_PORT,
    RABBIT_USER,
    RABBIT_PASS
);

$channel = $connection->channel();
$channel->queue_declare('emails', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$reciveCallback = function ($msg) use($opt) {
    $file = "/var/www/html/log/" . $opt["name"] . ".log";
    file_put_contents($file, $opt["name"] . " " . now() . " " .$msg->body . "\n", FILE_APPEND);
    try {
        send_mail();
        $msg->ack();
    } catch (Exception $e) {
        //log failed send_mail
    }
    echo ' [x] Received '.$opt["name"], " ", $msg->body, "\n";

};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('emails', '', false, false, false, false, $reciveCallback);

while ($channel->is_open()) {
    $channel->wait();
}