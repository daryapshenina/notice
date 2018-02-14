<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;
require '../vendor/autoload.php';
require '../bin/sendSocket.php';
/**
 * Created by PhpStorm.
 * User: root
 * Date: 08.02.18
 * Time: 19:18
 */
class Consumer
{

function __construct()
{
    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();
    $channel->queue_declare('hello', false, false, false, false);
    echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

    $callback = function($msg) {
        echo " [x] Received ", $msg->body, "\n";
        $WebSocketClient = new WebsocketClient('localhost', 8082);
        echo $WebSocketClient->sendData($msg->body);
        unset($WebSocketClient);
    };


    $channel->basic_consume('hello', '', false, true, false, false, $callback);
    while(count($channel->callbacks)) {
        $channel->wait();
    }
    $connection->close();
    $channel->close();
}
}