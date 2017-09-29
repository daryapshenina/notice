<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.08.17
 * Time: 11:17
 */

namespace Search\Model;

use Mobileinspector\Basic\ZendHttpClient;
use Search\Module;

abstract class AbstractServiceLog
{
    //public $uri = 'http://gibdd-nginx/logging/save';
    public $service;
    public $imei;
    public $token;

    public function __construct()
    {
        $this->client = new ZendHttpClient;
        $this->getUri();
    }
    public function getUri(){
        $configs = new Module;
        $this->params = $configs->getConfig()['search'];
        $this->uri = $this->params['LOG_URL'];
    }

    /**
     * Отправка данных в сервис логирования
     * @param $arrayLog
     * @param $logLevel
     * @throws \Exception
     */
    public function saveLog($arrayLog, $logLevel)
    {
        $arrayLog = $this->setBasicData($arrayLog, $logLevel);
        $this->client->post($this->uri, json_encode($arrayLog));
        //ToDo некрасиво получается ошибка, как-нибудь исправить
        if ($this->client->statusCode() !== 200) {
            throw new \Exception($this->client->responseBodyRaw());
        }
    }

    /**
     * Заполнение необходимых данных
     * @param $arrayLog
     * @param $logLevel
     * @return mixed
     */
    public function setBasicData($arrayLog, $logLevel)
    {
        $arrayLog['imei'] = $this->imei;
        $arrayLog['token'] = $this->token;
        $arrayLog['level'] = $logLevel;
        $arrayLog['service'] = $this->service;
        return $arrayLog;
    }
}