<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:29
 */

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class Fis extends ImpulsMService implements ServiceInterface
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
        $this->url = 'driver/fis';
        $this->send($request);
    }

    public function getResponse()
    {
        $this->response = !empty($this->response->data[0]) ? $this->response->data[0] : $this->response;
        $this->findDrivers();
        $this->getResult();
        $this->log();
    }

    public function log()
    {
        $this->responseLogger->request = $this->requestES;
        $this->searchLogger->type = 'ФИС';
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
            $this->$logger->service = 'fis';
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
        foreach ($this->request as $key => $value) {
            ${$key} = $value;
        }
        $yo = array('ё' => 'е', 'Ё' => 'Е');
        $requestES->surname = mb_strtoupper(strtr($surname, $yo), 'utf8');
        $requestES->name = mb_strtoupper(strtr($name, $yo), 'utf8');
        $requestES->patronymic = mb_strtoupper(strtr($patronymic, $yo), 'utf8');

        //ToDo - пока нее знаю  в каком виде будет запрос. Возможно оставить только один метод получение даты рождения

        /*Это получение даты, если будет приходит отдельно subdate и год рождения*/
        if (!isset($subdate)) {
            $date = strtotime($this->request['year']);
            $year = date('Y', $date);
            $subdate = date('dm', $date);
        }
        $requestES->subdate = $subdate;
        $requestES->year = $year;
        $this->searchLogger->search_string = $name . ' ' . $surname . ' ' . $patronymic . ' ' . $subdate[0] . $subdate[1] . '.' . $subdate[2] . $subdate[3] . '.' . $year;
        return $requestES;
    }

    /**
     * ToDo вообще не уверена, что метод нужен, нигде не нашла свойство wanted в результатах по fis
     * Определение находится ли человек в розыске
     */
    public function getWanted()
    {
        $this->searchLogger->wanted = false;
        foreach ($this->response->drivers as $val) {
            if (isset($val->wanted)) {
                foreach ($val->wanted as $k => $w) {
                    if (!isset($w->num_cirk_sr) || $w->num_cirk_sr == null) {
                        $this->searchLogger->wanted = true;
                    }
                }
            }
        }
    }

    /**
     * Удаление повторяющихся водителей
     */
    public function findDrivers()
    {
        $hashes = array();
        foreach ($this->response->drivers as $k => $val) {
            $sha = sha1(json_encode($val));
            if (in_array($sha, $hashes)) {
                unset($this->response->drivers[$k]);
                continue;
            } else {
                $hashes[] = $sha;
            }
        }
        sort($this->response->drivers);
    }

    public function isError()
    {
        return isset($this->response->responseCode) && mb_strtoupper($this->response->responseCode) === "ERROR";
    }

    /**
     * Добавление decision для совместимости с клиентом
     */
    public function getResult()
    {
        if ($this->isError()) {
            $this->result = $this->response;
        }
        if (is_array($this->response->drivers)) {
            foreach ($this->response->drivers as $key => $driver) {

                if (is_array($driver->violations)) {
                    foreach ($driver->violations as $vkey => $violation) {
                        if (is_array($violation->measures)) {
                            foreach ($violation->measures as $mkey => $measure) {

                                if (!isset($measure->decision)) {

                                    $this->response->drivers[$key]->violations[$vkey]->measures[$mkey]->decision = '';

                                }

                            }
                        }
                    }
                }
            }
            $this->result = $this->response->drivers;
        }
    }
}