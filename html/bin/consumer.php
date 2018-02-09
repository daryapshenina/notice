<?php
//Подключаем нужный класс]]

require '/var/www/html/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
require 'sendSocket.php';

//Создаем соединение
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

//Берем канал и декларируем в нем очередь, важно чтобы названия очередей совпадали
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

//Функция, которая будет обрабатывать данные, полученные из очереди
$callback = function($msg) {
    //echo " [x] Received ", $msg->body, "\n";
    $WebSocketClient = new WebsocketClient('localhost', 8082);
    echo $WebSocketClient->sendData($msg->body);
    unset($WebSocketClient);
};

//Уходим слушать сообщения из очереди в бесконечный цикл
$channel->basic_consume('hello', '', false, true, false, false, $callback);
while(count($channel->callbacks)) {
    $channel->wait();
}

//Не забываем закрыть соединение и канал
$channel->close();
$connection->close();

?>