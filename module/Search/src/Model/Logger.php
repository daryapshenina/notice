<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:30
 */

namespace Search\Model;
/*Пока нынешний функционал abstractservicelogger полежит тут*/

Class Logger
{


    public $responseCode;
    public $originalResponse;
    public $response;
    public $request;
    public $user;
    public $service;
    public $uri;
    public $subtype;
    public $type;
    public $wanted;
    public $mode;
    public $longitude;
    public $latitude;
    public $search_string;


    public function saveLog()
    {
        $basicLogArray = $this->basicLogArray();
        $this->loggingSearch($this->searchLogPrepare(), $basicLogArray);
        $this->loggingResponse($this->request, $this->response, $basicLogArray);
        $this->loggingService($this->uri, $this->originalResponse, $this->responseCode, $this->user->login, $basicLogArray);
        if (($this->service = 'driver/fio') or ($this->service = 'license') or ($this->service = 'fis')) {
            $this->loggingRestraint($this->response, $basicLogArray);
        }
    }

    public function loggingResponse($request, $response, $basicLogArray)
    {
        $logger = new ResponseLogger();
        $logger->send($request, $response, $basicLogArray);
    }

    public function loggingRestraint($response, $basicLogArray)
    {
        $logger = new RestraintLogger();
        $logger->prepareLog($response, $basicLogArray);
    }

    public function loggingService($uri, $response, $code, $login, $basicLogArray)
    {
        $logger = new ServiceLogger();
        $logger->send($uri, $response, $code, $login, $basicLogArray);
    }

    public function loggingSearch($array, $basicLogArray)
    {
        $logger = new SearchLogger();
        $logger->send($array, $basicLogArray);
    }

    /* Получение данных  и сами данные будут изменяться*/
    public function searchLogPrepare()
    {
        $logArray = array();
        $logArray['subtype'] = $this->subtype;
        $logArray['inspector'] = $this->user->login;
        $logArray['division'] = $this->user->ogaiCode;
        $logArray['type'] = $this->type;
        $logArray['mode'] = $this->mode;
        $logArray['result_code'] = isset($this->response->responseCode) ? $this->response->responseCode : '';
        $logArray['longitude'] = $this->longitude;
        $logArray['latitude'] = $this->latitude;
        $logArray['response_comment'] = isset($this->response->responseComment) ? $this->response->responseComment : '';
        $logArray['wanted'] = $this->wanted ? 1 : 0;
        $logArray['search_string'] = $this->search_string;
        $logArray['imei'] = $this->user->imei;
        // $logArray['main_screen'] = $this->user->main_screen;
        // $logArray['original_press]' =$this->user->original_press;
        return $logArray;
    }

    public function basicLogArray()
    {
        $basicLogArray = array();
        $basicLogArray['imei'] = $this->user->imei;
        $basicLogArray['token'] = $this->user->token;
        $basicLogArray['service'] = $this->service;
        return $basicLogArray;
    }



}