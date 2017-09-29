<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 13:57
 */

namespace Search\Model;

use Mobileinspector\Basic\ZendHttpClient;
use Search\Module;

class ImpulsMService
{
    public $responseCode;
    public $originalResponse;
    public $response;
    public $request;
    public $userInfo;
    public $service;
    public $uri;
    public $url;
    public $subtype;
    public $type;
    public $wanted;
    public $longitude;
    public $latitude;
    public $requestES;
    public $search_string;
    public $client;
    public $headers = array('Content-Type' => 'application/json; charset=utf-8');

    /**
     * Отправка запроса во внешний сервис
     * @param $url
     * @param $request
     * @param $info
     */
    public function send($request)
    {
        $this->client = new ZendHttpClient;
        $this->makeRequest($request);
        $this->getUrl();
        $this->client->post($this->uri, $this->requestES, $this->headers);
        $this->response = $this->client->responseBodyAsJSON();
        $this->originalResponse = $this->response;
        $this->responseCode = $this->client->statusCode();
    }

    public function getUrl(){
        $configs = new Module;
        $this->params = $configs->getConfig()['search'];
        $this->uri = $this->params['ES_URL'] . $this->url;
    }

    public function makeRequest($request)
    {
        $this->requestES = new \stdClass();
        $client = new \stdClass();
        $client->username = $this->userInfo->login;
        $client->password = $this->userInfo->password;
        $client->ogaiCode = $this->userInfo->ogaiCode;
        $client->ip = $this->userInfo->ip;
        $client->schema = 'GIBDD_APR';
        $this->requestES->client = $client;
        foreach ($request as $property => $value) {
            $this->requestES->$property = $value;
        }
        $this->requestES = json_encode($this->requestES);
    }

    public function getAddress()
    {
        /*Получение адреса из sphinx*/
        return true;
    }


//ToDo дописать тест Как-то изменить этот метод, возможно добавить список ошибок сервиса

    /**
     * Обработка ответа внешнего сервиса на ошибки
     */
    public function checkResponse($not_found)
    {
        if ((isset ($this->response->responseCode)) and ($this->response->responseCode === 'OK')) {
            $this->response->responseComment = null;
        } else {
            $this->response->responseCode = 'ERROR';
            $this->response->responseComment = isset($this->response->responseComment) ? $this->response->responseComment : $not_found;
        }
    }
    //ToDo возможно сделать список ошибок
    /*  $this->original_comment = isset($this->response->responseComment) ? $this->response->responseComment : null;
      if (!isset($this->response->responseCode)) {
          if (!is_object($this->response)) $this->response = new stdClass();

          $this->response->responseComment = 'Нет соединения с базой. Повторите попытку позже.';
          $this->response->responseCode = 'ERROR';

      } else if ($check_vehicles && (count($this->response->vehicles) == 0) && (count($this->response->wantedVehicles) == 0) && (count($this->response->wantedSP) == 0) && empty($this->response->responseComment)) {
          $this->response->responseComment = 'Ничего не найдено — нет информации в базе транспортных средств.';
          $this->response->responseCode = 'ERROR';

      } else if ($this->response->responseCode === 'ERROR') {

          if ($this->response->responseComment == '') {

              $this->response->responseComment = 'Неизвестная ошибка. Повторите попытку позже.';

          } else if (strstr($this->response->responseComment, 'Нарушение прав доступа по времени')) {

              $this->response->responseComment = 'Нарушение прав доступа по времени';

          } else if ((strstr($this->response->responseComment, 'ORA') || strstr($this->response->responseComment, 'could not find program unit'))) {

              $this->response->responseComment = $not_found;

          }

      } else if ($this->response->responseCode === 'OK') {
          $this->response->responseComment = null;
      }
  }

  /**
   * Транслитерация
   * @param $str
   * @return string
   */
    public function translit($str)
    {
        $str = mb_strtoupper($str, 'utf-8');
        $tr = array(
            "A" => "А",
            "B" => "В",
            "E" => "Е",
            "K" => "К",
            "M" => "М",
            "H" => "Н",
            "O" => "О",
            "P" => "Р",
            "C" => "С",
            "T" => "Т",
            "Y" => "У",
            "X" => "X"
        );
        return $str = strtr($str, $tr);
    }
}