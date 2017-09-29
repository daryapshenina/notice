<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:30
 */

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class Taxi implements ServiceInterface
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

    public function createRequest()
    {
        $this->request['request'] = $this->request['plate_number'];
        $this->request['fixDate'] = date('d.m.Y');
        $this->request['licPlateNum'] = $this->request['plate_number'];
        $taxiRequest = $this->request;
        return $taxiRequest;

    }

    public function search($request)
    {
        $this->taxuRequest = $request;
        $this->spec->setRequest($request);
        $this->spec->setUserInfo($this->userInfo);
        $requestSpecTr = $this->spec->createRequest();
        $this->spec->search($requestSpecTr);

        $this->spec->getResponse();
        if (!is_object($this->spec->response)) $this->external_errors = true;
        if ($this->spec->response->result->place[0]['name'] === 'Выделенные полосы для общественного транспорта') {
            $this->vehicle->setRequest($request);
            $this->vehicle->setUserInfo($this->userInfo);
            $this->vehicle->setType('licensePlateNumber');
            $requestVehicle = $this->vehicle->createRequest();
            $this->vehicle->search($requestVehicle);
            $this->vehicle->getResponse();
        };

    }

    public function getResponse()
    {
        $responseData = $this->checkResponseData();
        $result = $this->makeResponse($responseData);
        $this->getResult($result);
        $this->log();
    }

    public function getResult($result)
    {
        $this->responseCode = $result->responseCode;

        if (!isset($result->TaxiInfo)) {

            $this->result = null;
            $this->comment = $result->errorComment;

        } else {

            $this->result = $result->TaxiInfo;
        }
    }


    public function getError()
    {
        $error = new \stdClass();
        $error->errorComment = 'Транспортное средство гос. номер ' . $this->plate_number . ' не зарегистрировано в реестре такси.';
        $error->responseCode = 'ERROR';
        return $error;
    }

    public function checkResponseData()
    {
        $result = $this->vehicle->response;
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

    public function makeResponse($responseData)
    {
        if ($responseData) {

            $out = new \stdClass();
            $out->TaxiInfo = $responseData;
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

    public function getExternalErrors()
    {
        return $this->external_errors;
    }

    public function log()
    {
        $this->responseLogger->request = json_encode($this->taxuRequest);
        $this->searchLogger->type = 'Такси';

        $this->searchLogger->response_comment = isset($this->response->errorComment) ? $this->response->errorComment : '';
        $this->searchLogger->response_code = isset($this->response->responseCode) ? $this->response->responseCode : '';
        $this->searchLogger->longitude = $this->request['longitude'];
        $this->searchLogger->latitude = $this->request['latitude'];
        //TODO изменить урл?
        $this->serviceLogger->url = 'taxi';
        $this->searchLogger->search_string = $this->request['plate_number'];
        $this->serviceLogger->response = $this->response;
        $this->serviceLogger->code = $this->response->responseCode;
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger', 'restraintLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $this->request['imei'];
            $this->$logger->service = 'taxi';
            $this->$logger->token = 'token'/*$array['token']*/
            ;
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
    }

}
