<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25.09.17
 * Time: 14:36
 */
namespace Application\Controller;
use Ratchet\Server\IoServer;
use Application\Model\Chat;
use Ratchet\WebSocket\WsServer;
$server = IoServer::factory(
    new WsServer(
        new Chat()
    )
    , 8080
);

$server->run();
