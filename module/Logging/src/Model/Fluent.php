<?php

namespace Logging\Model;

use Fluent\Logger\FluentLogger;
use Logging\Module;

class Fluent
{
    public $FluentLoggerClient;


    public function __construct()
    {
        $this->getParams();
        $this->FluentLoggerClient = FluentLogger::open($this->host, $this->port, $this->options);
    }

    /**
     * Получение параметров для Fluent
     */
    private function getParams()
    {

        $this->configs = new Module;
        $params = $this->configs->getConfig()['fluent'];
        $this->host = $params['host'];
        $this->port = $params['port'];
        $this->options = $params['options'];
    }

    /**
     * @param $tag - тег
     * @param $data - массив для записи в mongo
     */
    public function post($tag, $data)
    {
        $this->FluentLoggerClient->post($tag, $data);
    }
}