<?php
/*/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:31
 */

namespace Search\Model;

use Zend\Http\Client;


//SearchLog

class SearchLogger extends AbstractServiceLog
{

    public $request;
    public $response;
    public $userInfo;
    public $type;
    public $mode;
    public $longitude;
    public $latitude;
    public $wanted;
    public $search_string;
    public $response_comment;
    public $response_code;

    /**
     * Подготовка данных для логирования
     * @param $request
     * @param $response
     * @param $basicLogArray
     * @return mixed
     */
    public function prepareLog()
    {
        $basicLogArray = array();
        $basicLogArray['id_service'] = 'SearchLogger';
        $basicLogArray['createDate'] = date('Y-m-d H:s:i', time());
        $basicLogArray['type'] = $this->type;
        $basicLogArray['result_code'] = $this->response_code;
        $basicLogArray['response_comment'] = $this->response_comment;
        $basicLogArray['longitude'] = $this->longitude;
        $basicLogArray['latitude'] = $this->latitude;
        $basicLogArray['wanted'] = $this->wanted ? 1 : 0;
        $basicLogArray['search_string'] = $this->search_string;
        return $basicLogArray;
    }

    /**
     * Отправка данных в сервис логирования
     * @param $request
     * @param $response
     * @param $basicLogArray
     */
    public function send()
    {
        $arrayLog = $this->prepareLog();
        $logLevel = 2;
        $this->saveLog($arrayLog, $logLevel);
    }
}