<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection(
    RABBIT_HOST,
    RABBIT_PORT,
    RABBIT_USER,
    RABBIT_PASS
);

$channel = $connection->channel();
echo "\n";

$exit = false;
while(!$exit) {
    sleep(5);
    list($queueName, $messageCount, $consumerCount) = $channel->queue_declare('emails', false, false, false, false);
    echo $messageCount . "\n";
    if ($messageCount == 0) {
        $exit = true;
        echo "run kllier\n";
        exec("/bin/sh /var/www/html/bash/killer.sh");
        die();
    }

}
echo "exit killer\n";
if($exit) exit();