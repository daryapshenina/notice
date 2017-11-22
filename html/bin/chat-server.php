<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.11.17
 * Time: 13:34
 */
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require '../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

//Берем канал и декларируем в нем новую очередь, первый аргумент - название
$channel = $connection->channel();
$channel->queue_declare('hello', false, false, false, false);

//Создаем новое сообщение
$msg = new AMQPMessage('Hello World!');
//Отправляем его в очередь
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";

//Не забываем закрыть канал и соединение
$channel->close();
$connection->close();