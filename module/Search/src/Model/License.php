<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:29
 */

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class License extends ImpulsMService implements ServiceInterface
{
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;
    public $restraintLogger;

    public function __construct(ResponseLogger $responseLogger, SearchLogger $searchLogger, ServiceLogger $serviceLogger, RestraintLogger $restraintLogger)
    {
        $this->responseLogger = $responseLogger;
        $this->searchLogger = $searchLogger;
        $this->serviceLogger = $serviceLogger;
        $this->restraintLogger = $restraintLogger;
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
     * Отправка запроса
     * @param $request
     */
    public function search($request)
    {
        $this->url = 'driver';
        $this->send($request);
    }

    public function getResponse()
    {
        $this->getAddress();
        $this->checkResponse('ВУ номер ' . $this->search_string . ' не найдено в базе. Проверьте введенные значения.');
        $this->result = $this->response;
        $this->log();
    }

    public function log()
    {
        $this->getWanted();
        $this->responseLogger->request = $this->requestES;
        $this->searchLogger->type = 'Водительское удостоверение';
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
            $this->$logger->service = 'license';
            $this->$logger->token = 'token';
        }
        $this->responseLogger->response = $this->response;
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
        $this->dataRestraint();
    }

    public function dataRestraint()
    {
        if (!isset($this->response->drivers)) return false;

        foreach ($this->response->drivers as $value) {
            if (isset($value->wanted)) {
                foreach ($value->wanted as $wanted) {
                    $this->restraintLogger->restraint = $wanted->restraint;
                    $this->restraintLogger->num_cirk_sr = $wanted->num_cirk_sr;
                    $this->restraintLogger->date_ust = $wanted->date_ust;
                    $this->restraintLogger->send();
                }
            }
        }
        return true;
    }

    public function createRequest()
    {
        $requestES = new \stdClass();
        $license = mb_strtoupper($this->request["driver_license"], 'utf-8');
        $this->searchLogger->search_string = $license;
        $license = $this->translit($license);
        $requestES->driverLicense = $license;
        return $requestES;
    }

    public function getWanted()
    {
        if (!isset($this->response->drivers)) return false;

        foreach ($this->response->drivers as $value) {
            if (isset($value->wanted)) {
                foreach ($value->wanted as $wanted) {
                    if (!isset($wanted->num_cirk_sr) || $wanted->num_cirk_sr == null) $this->searchLogger->wanted = true;
                }
            }
        }

        return true;
    }

}