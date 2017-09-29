<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Application\Model\Chat;
use Ratchet\Server\IoServer;


class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $server = IoServer::factory(
            new WsServer(
                new Chat()
            )
            , 8080
        );

        $server->run();

    }

}

