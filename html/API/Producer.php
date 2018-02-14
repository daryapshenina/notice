<?php
require '../vendor/autoload.php';
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12.02.18
 * Time: 10:45
 */

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    public $connection;
    public $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    }
    public function setChannel(/*$channelName*/){
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare('hello', false, false, false, false);
//        Создаем канал с определенным названием
//        $channel->queue_declare($channelName, false, false, false, false);
    }

    public function createMessage(/*$channelName*/){
        $msg = new AMQPMessage('Hello World!');
        $this->channel->basic_publish($msg, '', 'hello');
//        Создаем сообщение в определенную очередь
//        $this->channel->basic_publish($msg, '', $channelName);
        $this->channel->close();
        $this->connection->close();
    }


}