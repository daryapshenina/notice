<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:31
 */

namespace Search\Model;

use Zend\Http\Client;

//ServiceLog
class ServiceLogger extends AbstractServiceLog
{
    public $url;
    public $response;
    public $code;

    public function prepareLog()
    {
        $basicLogArray['url'] = $this->url;
        $basicLogArray['id_service'] = 'ServiceLogger';
        $basicLogArray['code'] = $this->code;
        $basicLogArray['original'] = $this->response;
        return $basicLogArray;
    }

    public function send()
    {
        $arrayLog = $this->prepareLog();
        $logLevel = 2;
        $this->saveLog($arrayLog, $logLevel);
    }

}

