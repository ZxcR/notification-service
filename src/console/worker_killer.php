<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "./index.php";

$channel = $connection->channel();

while(true) {
    sleep(5);
    list($queueName, $messageCount, $consumerCount) = $channel->queue_declare('emails', false, false, false, false);
    echo $messageCount . "\n";
    if ($messageCount == 0) {
        echo "run kllier\n";
        exec("/bin/sh /var/www/html/bash/killer.sh");
        die();
    }

}