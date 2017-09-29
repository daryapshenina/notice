<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:30
 */

namespace Search\Model;

use Zend\Http\Client;

class RestClient
{
    public function __construct()
    {
        $client = new Client();
        $client->setMethod('Post');
        $this->request = new \stdClass();
        $this->request->client = new \stdClass();

    }
}