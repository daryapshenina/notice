<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:29
 */

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class Wanted extends ImpulsMService implements ServiceInterface
{

    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;
    public $restraintLogger;

    public function __construct(ResponseLogger $responseLogger, SearchLogger $searchLogger, ServiceLogger $serviceLogger)
    {
        $this->responseLogger = $responseLogger;
        $this->searchLogger = $searchLogger;
        $this->serviceLogger = $serviceLogger;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }

    public function search($request)
    {
        $this->url = 'wanted';
        $this->send($request);
    }

    public function getResponse()
    {
        $this->getResult();
        $this->log();
    }

    /**
     * Логирование
     * @param $array
     * @param $info
     * @return string
     */
    public function log()
    {
        $this->responseLogger->request = $this->requestES;
        $this->searchLogger->type = 'Поиск по розыску';
        $this->getWanted();
        $this->searchLogger->response_comment = isset($this->response->responseComment) ? $this->response->responseComment : '';
        $this->searchLogger->response_code = isset($this->response->responseCode) ? $this->response->responseCode : '';
        $this->searchLogger->longitude = $this->request['longitude'];
        $this->searchLogger->latitude = $this->request['latitude'];
        $this->serviceLogger->url = $this->uri;
        $this->serviceLogger->code = $this->responseCode;
        $this->serviceLogger->response = $this->originalResponse;
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger', 'restraintLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $this->request['imei'];
            $this->$logger->service = 'wanted';
            $this->$logger->token = 'token';
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
    }

    /**
     * Подготовка параметров запроса
     * @param $array
     * @return \stdClass
     */
    public function createRequest()
    {
        foreach ($this->request as $key => $value) {
            ${$key} = $value;
        }
        $requestES = new \stdClass();
        $requestES->surname = mb_strtoupper($this->request['surname']);
        $requestES->name = mb_strtoupper($this->request['name']);
        $requestES->year = mb_strtoupper($this->request['year']);
        $this->searchLogger->search_string = "$surname $name $year";
        return $requestES;
    }

    /**
     * Получение результата и комментария.
     */
    public function getResult()
    {
        if (!empty($this->response->responseCode) and $this->response->responseCode === 'OK') {

            $this->result = empty($this->response->wanted) ? null : $this->response->wanted;

        } else {

            $this->result = null;

        }

        $this->comment = empty($this->response->responseComment) ? '' : $this->response->responseComment;
    }

    /**
     * Определение находится человек в розыске или нет
     */
    public function getWanted()
    {
        $this->searchLogger->wanted = false;
        if (count($this->response->wanted) > 0) {
            foreach ($this->response->wanted as $k => $w) {
                if (!isset($w->num_cirk_sr) || $w->num_cirk_sr == null) {
                    $this->searchLogger->wanted = true;
                }
            }
        }
    }
}