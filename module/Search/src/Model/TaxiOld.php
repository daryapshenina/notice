<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.09.17
 * Time: 12:45
 */

namespace Search\Model;


class TaxiOld
{
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;

    public function __construct(ResponseLogger $responseLogger, SearchLogger $searchLogger, ServiceLogger $serviceLogger)
    {
        $this->responseLogger = $responseLogger;
        $this->searchLogger = $searchLogger;
        $this->serviceLogger = $serviceLogger;
        $this->spec = new SpecialTransport($this->responseLogger, $this->searchLogger, $this->serviceLogger);
        $this->vehicle = new Vehicle($this->responseLogger, $this->searchLogger, $this->serviceLogger);
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
     * Поиск по спецтранспорту
     * @param $array
     * @param $info
     */
    public function prepare($array, $info)
    {
        $this->user = $info;
        $specArray = $this->createRequestSpec($array);
        $special_transport = $this->spec->prepare($specArray, $info);
        if (!is_object($special_transport)) $this->external_errors = true;
        $res = $this->getVehicleData($special_transport, $array, $info);
        $result = $this->makeResponse($res);

        if (!isset($result->TaxiInfo)) {

            $this->result = null;
            $this->comment = $result->errorComment;

        } else {

            $this->result = $result->TaxiInfo;
        }
        //Логирование

    }

    public function log(){
        //$this->responseLogger->request = $this->request;
        $this->searchLogger->type = 'Такси';

        $this->searchLogger->response_comment = isset($this->response->errorComment) ? $this->response->errorComment : '';
        $this->searchLogger->response_code = isset($result->responseCode) ? $result->responseCode : '';
        $this->searchLogger->longitude = $array['longitude'];
        $this->searchLogger->latitude = $array['latitude'];
        //TODO изменить урл?
        $this->serviceLogger->url = 'taxi';
        $this->searchLogger->search_string = $this->plate_number;
        $this->serviceLogger->response = $this->response;
        $this->serviceLogger->code = $this->responseCode;
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger', 'restraintLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $array['imei'];
            $this->$logger->service = 'taxi';
            $this->$logger->token = 'token'/*$array['token']*/
            ;
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
    }

    /**
     * return $external_errors
     * @return mixed
     */
    public function getExternalErrors()
    {
        return $this->external_errors;
    }

    public function makeResponse($res)
    {
        if ($res) {

            $out = new \stdClass();
            $out->TaxiInfo = $this->data;
            $out->responseCode = 'OK';
            $this->response = isset($out) ? $out : $this->getError();

            if ($this->getExternalErrors()) {
                return false;
            }
            return $out;

        } else {
            $this->response = $this->getError();
            return $this->getError();

        }
    }

    /**
     * В зависимости от ответа сервиса спецтранспорта делаем поиск по водительскому удостоверению
     * @param $special_transport
     * @param $array
     * @param $info
     * @return bool
     */
    public function getVehicleData($special_transport, $array, $info)
    {
        //Очень странный момент для теста
        if (is_array($special_transport->place[0])) {
            $name = $special_transport->place[0]['name'];
        } elseif (is_object($special_transport->place[0])) {
            $name = $special_transport->place[0]->name;
        }
        if ($name === 'Выделенные полосы для общественного транспорта') {
            $vehicleArray = $this->createRequestVehicle($array);
            $this->vehicle->sendRequest($vehicleArray, 'licensePlateNumber', $info);
            $result = $this->vehicle->response;
            if (!is_object($result)) $this->external_errors = true;
            $this->data = $this->checkResponseData($result);

        } else {

            return false;
        }

        return true;
    }


    /**
     * Подготовка массива запроса для получение инфомации о ГРЗ
     * @param $array
     * @return mixed
     */
    public function createRequestVehicle($array)
    {
        $array['request'] = $this->plate_number;
        return $array;
    }

    /**
     * Тест есть
     * Составление комментария при ошибке
     * @return \stdClass
     */
    public function getError()
    {
        $error = new \stdClass();
        $error->errorComment = 'Транспортное средство гос. номер ' . $this->plate_number . ' не зарегистрировано в реестре такси.';
        $error->responseCode = 'ERROR';
        return $error;
    }

    /**
     * Создание данных для поиска по спецтранспорту
     * @param $array
     * @return array
     */
    public function createRequestSpec($array)
    {
        $this->plate_number = $array['plate_number'];
        $specArray = $array;
        $specArray['fixDate'] = date('d.m.Y');
        $specArray['licPlateNum'] = $this->plate_number;
        return $specArray;
    }


    /**
     * Получение данных из ответа поиска по ГРЗ (Водительское удостоверение)
     * @param $result
     * @return bool|\stdClass
     */
    public function checkResponseData($result)
    {
        if (count($result->vehicles) > 0) {
            $taxi = $result->vehicles[0];
            $taxiData = new \stdClass();
            $taxiData->Name = $taxi->ownerFirstName;
            $taxiData->Address = $taxi->street;
            $taxiData->Address .= !empty($taxi->house) ? ', ' . $taxi->house : '';
            $taxiData->Address .= !empty($taxi->flat) ? ', ' . $taxi->flat : '';
            $taxiData->Brand = $taxi->brand;
            $taxiData->Model = $taxi->model;
            $taxiData->RegNum = $taxi->plateNumber;
            $taxiData->LicenseNum = 'Имеется действующая лицензия';
            $taxiData->Year = $taxi->vehicleYear;
            return $taxiData;

        } else {

            return false;
        }
    }
}