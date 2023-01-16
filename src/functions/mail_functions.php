<?php
function check_mail($email) {
    $sleep_time = random_int(1, 60);
    sleep($sleep_time);
    return true;
}

function send_mail() {
    $sleep_time = random_int(1, 10);
    sleep($sleep_time);
    return true;
}