<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.09.17
 * Time: 11:05
 */

namespace Search\Model;

//// Имеющийся функционал класса vehicle
class NewVehicle
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

    /**
     * @param $array
     * @param $info
     */
    public function prepare($array, $info)
    {
        $params = array(
            "licensePlateNumber", "sts", "pts", "vin", "chassisNumber", "bodyNumber", "engineNumber"
        );
        $this->out = array('wantedVehicles' => array(), 'vehicles' => array(), 'wantedSP' => array());
        foreach ($params as $param) {

            $this->sendRequest($array, $param, $info);
            $this->checkAllServiceResponse($param);
        }

        $this->result = $this->out;
    }


    /**
     * Получение ответа внешнего сервиса и подготовка данных для логирования
     * @param $array
     * @param $param
     * @param $info
     */
    public function sendRequest($array, $param, $info)
    {
        $request = $array['request'];
        $this->service = 'vehicle';
        $request = $this->vehicleData($request, $param);
        $this->send($this->url, $request, $info);
        $this->responseData();
        $this->checkResponse('ТС номер (' . $this->param_names[$this->subtype] . ') ' . $this->search_string . ' не найдено в базе. Проверьте правильность.', true);
        //Логирование
        $this->getWanted();
        $this->responseLogger->request = $this->request;
        $this->searchLogger->response_comment = isset($this->response->responseComment) ? $this->response->responseComment : '';
        $this->searchLogger->response_code = isset($this->response->responseCode) ? $this->response->responseCode : '';
        $this->searchLogger->longitude = $array['longitude'];
        $this->searchLogger->latitude = $array['latitude'];
        $this->serviceLogger->url = $this->uri;
        $this->serviceLogger->code = $this->responseCode;
        $this->serviceLogger->response = $this->originalResponse;
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger', 'restraintLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $array['imei'];
            $this->$logger->service = $this->service;
            $this->$logger->token = 'token';
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
    }

    /**
     * Подготовка запроса в зависимости от типа
     * @param $plate_number
     * @param string $type
     * @return \stdClass
     */
    public function vehicleData($plate_number, $type = 'licensePlateNumber')
    {
        $this->searchLogger->search_string = $plate_number;
        $this->search_string = $this->translit($plate_number);
        $this->param_names = array(
            "licensePlateNumber" => "ГРЗ", "sts" => "СТС", "pts" => "ПТС",
            "vin" => "VIN", "chassisNumber" => "Номер шасси",
            "bodyNumber" => "Номер кузова", "engineNumber" => "Номер двигателя",
        );

        $is_plate_number = ($type === 'licensePlateNumber');
        $this->searchLogger->type = $is_plate_number ? 'Грз' : 'Спецпродукция «' . $this->param_names[$type] . '»';
        $this->url = $this->service = $is_plate_number ? 'vehicle' : 'stealing';
        $request = new \stdClass();
        $request->$type = $this->search_string;
        return $request;
    }


    /**
     * Обработка ответа внешнего сервиса
     * @param $response
     */
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

    /**
     * @param $param
     */
    public function checkAllServiceResponse($param)
    {
        $types = array('wantedVehicles', 'vehicles', 'wantedSP');
        foreach ($types as $type) {

            if (!empty($this->response->{$type})) {

                foreach ($this->response->{$type} as $item) {

                    if (is_object($item) and count(get_object_vars($item)) > 0) {

                        $item->source = $param;
                        $this->out[$type][] = $item;

                    }

                }
            }
        }
    }

    /**
     *
     */
    public function getWanted()
    {
        if (isset($this->response->wantedVehicles)) $this->searchLogger->wanted = true;;

    }

}