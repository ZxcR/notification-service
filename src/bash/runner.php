<?php

set_time_limit(300);
ini_set('memory_limit', '-1');

for ($i = 1; $i < 100; $i++) {
    echo "worker_{$i} started\n";
    exec("php /var/www/html/console/mail_send_consumer.php worker_{$i} > /dev/null 2>&1 &");
    echo "worker_{$i} runned\n";
}