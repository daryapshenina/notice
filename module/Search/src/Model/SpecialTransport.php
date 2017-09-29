<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:30
 */

namespace Search\Model;

use SoapClient;
use Search\Interfaces\ServiceInterface;

class SpecialTransport implements ServiceInterface
{
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;
    public $restraintLogger;
    public $request;

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
        $this->specTrRequest=$request;
        $this->url = 'http://10.11.32.135:6000/SpecialTransport/SpecialTransportDataQueryService';
        try {
            $this->originalResponse = $this->client->querySpecialTransportData($request);
        } catch (\SoapFault $e) {
            $this->errorMess = $e->getMessage();
        }
    }

    public function getResponse()
    {
        $SpecResult = $this->responseData();
        $this->getResult($SpecResult);
        $this->log();
    }

    //ToDo добавила странную обработку результата запроса

    /**
     * @param $array
     * @return mixed
     */
    public function log()
    {
        $this->responseLogger->request = json_encode($this->specTrRequest);
        $this->searchLogger->type = 'Спецтранспорт';
        $this->searchLogger->response_comment = isset($this->response->errorComment) ? $this->response->errorComment : '';
        $this->searchLogger->response_code = isset($this->response->errorCode) ? $this->response->errorCode : '';
        $this->searchLogger->longitude = $this->request['longitude'];
        $this->searchLogger->latitude = $this->request['latitude'];
        $this->serviceLogger->url = $this->url;
        $this->serviceLogger->response = json_encode($this->originalResponse);
        //ToDo подумать, как изменить /*$this->serviceLogger->code = $this->responseCode;*/
        $this->serviceLogger->code = isset($this->response->errorCode) ? $this->response->errorCode : '';
        $loggerArray = ['responseLogger', 'searchLogger', 'serviceLogger', 'restraintLogger'];
        foreach ($loggerArray as $argument => $logger) {
            $this->$logger->imei = $this->request['imei'];
            $this->$logger->service = 'specialtransport';
            //ToDO когда появится токен, заменить его получение
            $this->$logger->token = 'token'/*$this->request['token']*/
            ;
        }
        $this->responseLogger->response = json_encode($this->response);
        $this->responseLogger->send();
        $this->searchLogger->send();
        $this->serviceLogger->send();
        return $this->result;
    }

    /**
     * Подготовка Soap - запроса
     * @param $array
     * @return \stdClass
     */
    public function createRequest()
    {
        $this->searchLogger->search_string = $this->request['fixDate'] . ' ' . $this->request['licPlateNum'];
        $date = date('Y-m-d\TH:i:s', strtotime($this->request['fixDate']));
        $licPlateNum = mb_strtoupper($this->request['licPlateNum'], 'utf8');
        $wdsl = './module/Search/src/data/SpecialTransportDataQueryService.wsdl';
        $context = stream_context_create(array(
                'http' => array(
                    'protocol_version' => 1.0,
                    'timeout' => 2,
                    'ignore_errors' => true
                )
            )
        );
        $this->client = new SoapClient($wdsl, array('trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE, 'stream_context' => $context));;
        $requestSpecTr = new \stdClass();
        $requestSpecTr->records = new \stdClass();

        $requestSpecTr->records->rec = array("fixDate" => $date, "id" => 1,
            'licPlateNum' => $licPlateNum);
        return $requestSpecTr;

    }


    public function getResult($response)
    {

        if (empty($response) or !is_object($response)) {

            $this->result = null;
            $this->comment = 'Сервис поиска по спецтранспорту вернул некорректый ответ';

        } else {

            $this->result = $response->result;
            $this->comment = !empty($response->errorComment) ?
                $response->errorComment : '';

        }
    }

    public function responseData()
    {
        $this->response = new \stdClass();

        if (isset($this->originalResponse->records)) {
            $this->response->result->category = Array();
            $this->response->result->place = Array();
            foreach ($this->originalResponse->records as $item) {
                /*Получение категорий спец. транспорта*/
                $val = $this->getCategory($item);
                if ($val !== false) {
                    $this->response->result->category[] = $val;
                }
            }

            foreach ($this->originalResponse->records as $item) {

                /*Получение зоны, в которой разрешено нахождение*/
                $val = $this->getPlace($item);
                if ($val !== false) $this->response->result->place[] = $val;
            }

            $this->response->errorCode = "OK";
            $this->response->errorComment = "";
        } else {
            $this->response->errorCode = "ERROR";
            if (isset($this->errorMess)) {
                $this->response->errorComment = $this->errorMess;
            } else $this->response->errorComment = 'Поиск по спецтранспорту временно недоступен';

        }
        return $this->response;

    }


    //ToDo Сделать нормальные методы getCategory и getPlace

    /**
     * Получение категории спец.транспорта
     * @param $item
     * @return array
     */
    public function getCategory($item)
    {
        $categoryArray = array();
        $categoryArray['code'] = $item->category;
        switch ($item->category) {
            case '-888':
                $categoryArray['name'] = 'Не определено';
                break;
            case '0':
                $categoryArray['name'] = 'Такси';
                break;
            case '1':
                $categoryArray['name'] = 'Маршрутный транспорт';
                break;
            case '2':
                $categoryArray['name'] = 'Транспорт обслуживающий УДС';
                break;
            case '3':
                $categoryArray['name'] = 'Транспорт аварийных служб';
                break;
            case '4':
                $categoryArray['name'] = 'Транспорт экстренных служб';
                break;
            case '5':
                $categoryArray['name'] = 'Транспорт оперативных служб';
                break;
            case '6':
                $categoryArray['name'] = 'Транспорт службы ЖКХ';
                break;
            case '7':
                $categoryArray['name'] = 'Транспорт министерства, федеральной службы, инспекции, агентства';
                break;
            case '8':
                $categoryArray['name'] = 'Транспорт международной организации';
                break;
            case '9':
                $categoryArray['name'] = 'Иной транспорт';
                break;
            case '10':
                $categoryArray['name'] = 'Иной транспорт (категория ограниченного доступа)';
                break;
            case '11':
                $categoryArray['name'] = 'Грузовой транспорт';
                break;
        }
        return $categoryArray;
    }

    /**
     * Получение зоны, в которой разрешено нахождение спец.транспорта
     * @param $item
     * @return array
     */
    public function getPlace($item)
    {
        $placeArray = array();
        $placeArray['code'] = $item->place;
        switch ($item->place) {
            case '-888':
                $placeArray['name'] = 'Не определено';
                break;
            case '0':
                $placeArray['name'] = 'Выделенные полосы для общественного транспорта';
                break;
            case '1':
                $placeArray['name'] = 'Садовое кольцо';
                break;
            case '2':
                $placeArray['name'] = 'III транспортное кольцо';
                break;
            case '3':
                $placeArray['name'] = 'МКАД';
                break;
            case '4':
                $placeArray['name'] = 'Малая окружная железная дорога';
                break;
            case '5':
                $placeArray['name'] = 'Без ограничений';
                break;
            case '6':
                $placeArray['name'] = 'Московская область (МКАД)';
                break;
        }
        return $placeArray;

    }
}