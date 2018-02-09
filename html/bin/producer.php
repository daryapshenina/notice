<?php

//Файл с исходным кодом лежит в /application/hello-world, поэтому нужно спуститься на два уровня
//прежде, чем подключить vendor
//require '../vendor/autoload.php';
require '/var/www/html/vendor/autoload.php';
//Необходимые классы
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//Создаем соединение
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

?>