<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:29
 */

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class Vehicle extends ImpulsMService implements ServiceInterface
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

    public function setType($type)
    {
        $this->type = $type;
    }

    public function createRequest()
    {
        $this->searchLogger->search_string = $this->request['request'];
        $this->search_string = $this->translit($this->request['request']);
        $this->param_names = array(
            "licensePlateNumber" => "ГРЗ", "sts" => "СТС", "pts" => "ПТС",
            "vin" => "VIN", "chassisNumber" => "Номер шасси",
            "bodyNumber" => "Номер кузова", "engineNumber" => "Номер двигателя",
        );

        $is_plate_number = ($this->type === 'licensePlateNumber');
        $this->searchLogger->type = $is_plate_number ? 'Грз' : 'Спецпродукция «' . $this->param_names[$this->type] . '»';
        $this->url = $this->service = $is_plate_number ? 'vehicle' : 'stealing';
        $request = new \stdClass();
        $type = $this->type;
        $request->$type = $this->search_string;
        return $request;

    }

    public function search($request)
    {
        $this->send($request);

    }

    public function getResponse()
    {
        $this->responseData();
        $this->checkResponse('ТС номер (' . $this->param_names[$this->type] . ') ' . $this->request['request'] . ' не найдено в базе. Проверьте правильность.');
        $this->log();
    }

    public function responseData()
    {
        if (!isset($this->response->vehicles)) {

            if (empty($this->response)) {
                $this->responseBody = new \stdClass();
            }

            $this->response->vehicles = array();
            $this->response->vehicles[0] = new \stdClass();
        }
    }

    public function log()
    {
        $this->getWanted();
        $this->responseLogger->request = $this->requestES;
        $this->searchLogger->response_comment = isset($this->response->responseComment) ? $this->response->responseComment : '';
        $this->searchLogger->response_code = isset($this->response->responseCode) ? $this->response->responseCode : '';
        $this->searchLogger->longitude = $this->request['longitude'];
        $this->searchLogger->latitude = $this->request['latitude'];
        $this->serviceLogger->url = $this->uri;
        $this->serviceLogger->code = $this->responseCode;
        $this->serviceLogger->response = $this->originalResponse;
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $this->request['imei'];
            $this->$logger->service = $this->service;
            $this->$logger->token = 'token';
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
    }

    public function getWanted()
    {
        if (isset($this->response->wantedVehicles)) $this->searchLogger->wanted = true;;

    }
}