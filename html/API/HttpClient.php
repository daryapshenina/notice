<?php
require '../vendor/autoload.php';
use Guzzle\Http\Client;
use /** @noinspection PhpUndefinedNamespaceInspection */
    Guzzle\Http\Exception\ClientErrorResponseException;

use /** @noinspection PhpUndefinedNamespaceInspection */
    Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Message\Response;

/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.12.17
 * Time: 12:41
 */
class HttpClient
{

    public $http;
    public $request;

    public function __construct()
    {
        $this->init();

    }

    /**
     * Инициилизация httpClient
     */
    public function init()
    {
        $this->http = new Client();
    }

    /**
     * метод post
     * $uri - url
     */
    public function post($uri, $postBody, $headers = null)
    {
        $this->request = $this->http->post($uri, $postBody, $headers, array('exceptions' => false))->send();
        return $this;
    }

    /**
     * метод get
     */
    public function get($uri, $headers = null)
    {
        $this->request = $this->http->get($uri, $headers, array('exceptions' => false))->send();
        return $this;
    }

    /**
     * получение заголовков ответа
     */
    public function getHeader()
    {
        if ($this->request instanceof Response) {
            return $this->request->getRawHeaders();
        } else throw new Exception("Ошибка: запрос не был отправлен");
    }

    public function getStatusCode()
    {
        if ($this->request instanceof Response) {
            return $this->request->getStatusCode();
        } else throw new Exception("Ошибка: запрос не был отправлен");
    }

    /**
     * получение тела ответа
     */
    public function getBody()
    {
        if ($this->request instanceof Response) {
            $body = (string)$this->request->getBody();
            return $body;
        } else throw new Exception("Ошибка: запрос не был отправлен");
    }

    public function getUrl()
    {
        if ($this->request instanceof Response) {
            $url = (string)$this->request->getEffectiveUrl();
            return $url;
        } else throw new Exception("Ошибка: запрос не был отправлен");
    }

}