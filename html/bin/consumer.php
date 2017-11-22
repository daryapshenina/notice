<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.11.17
 * Time: 13:18
 */
use PhpAmqpLib\Connection\AMQPStreamConnection;
require '../vendor/autoload.php';
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

//
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();

}
$connection->close();
$channel->close();