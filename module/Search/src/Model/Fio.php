<?php

namespace Search\Model;

use Search\Interfaces\ServiceInterface;

class Fio extends ImpulsMService implements ServiceInterface
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
        var_dump($request);
        die;
        $this->send($request);
        var_dump($this->request);
        echo '<hr>';
        var_dump($this->response);
        die;
    }

    /**
     * Обработка ответа внешнего сервиса
     * @param $request
     */
    public function getResponse()
    {
        $this->getAddress();
        $this->checkResponse('Водитель с ФИО ' . $this->request['surname'] . ' ' . $this->request['name'] . ' ' . $this->request['patronymic'] . ', г.р. ' . $this->request['year'] . ' не найден в базе. Проверьте введенные значения.');
        $this->getResult();
        $this->log();
    }

    /**
     * Логирование данных сервиса
     * @param $array
     * @param $info
     */
    public function log()
    {
        $this->responseLogger->request = $this->requestES;
        $this->searchLogger->type = 'Поиск по ФИО';
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
            $this->$logger->service = 'driver/fio';
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

    /**
     * Подготовка параметров запроса из полученных данных
     * @param $array
     * @return \stdClass
     */
    public function createRequest()
    {
        $requestES = new \stdClass();
        $arguments = array(
            'name' => 'Имя водителя не заполнено',
            'surname' => 'Фамилия водителя не заполнена',
            'patronymic' => '',
            'year' => 'Год рождения водителя не заполнен'
        );

        $missing_arguments = false;
        foreach ($arguments as $argument => $error) {

            $array[$argument] = trim($this->request[$argument]);
            $array[$argument] = mb_strtoupper($this->request[$argument], 'utf8');
            if (($argument !== 'patronymic') && empty($this->request[$argument])) {

                $this->response->responseCode = 'ERROR';
                $this->response->responseComment[] = $error;
                $missing_arguments = true;

            }
            $this->search_string = $this->search_string . " " . $this->request[$argument];
            $requestES->$argument = $this->request[$argument];
        }
        $this->searchLogger->search_string = trim(str_replace("  ", " ", $this->search_string));
        if (!$missing_arguments) {
            $date = strtotime($this->request['year']);
            $requestES->year = date('Y', $date);
            $requestES->subdate = date('dm', $date);
        }
        return $requestES;
    }

    /**
     * Обработка ответа внешнего сервиса
     * @param $response
     */
    public function getResult()
    {

        if (!empty($this->response->responseCode) and $this->response->responseCode === 'OK') {

            $out = new \stdClass();
            $out->drivers = $this->response->drivers;

            if (!empty($this->response->photo)) {
                $out->photo = $this->response->photo;
            }

            $this->result = $out;

        } else {
            $this->result = null;
            $this->comment = !empty($this->response->responseComment) ? $this->response->responseComment : '';
        }
    }

    /**
     * Определение находится ли водитель в розыске
     * @return bool
     */
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