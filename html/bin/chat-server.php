<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16.11.17
 * Time: 13:34
 */
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;

require '../vendor/autoload.php';
require '../API/Chat.php';

//require '../init.php';
//require_once '../bootstrap.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8082
);

$server->run();