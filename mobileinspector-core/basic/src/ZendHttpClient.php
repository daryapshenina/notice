<?php
namespace Mobileinspector\Basic;

use Zend\Http\Request;
use Zend\Http\Headers;
use Zend\Http\Client;
use Zend\Http\Response;

/**
 * Обертка HTTP-клиента на основе Zend-HTTP
 * 
 * Задача GIBDDPRSL-204 "Реализовать обёртку над http в mobileinspector-core"
 *
 * @author SotnikovDS <sotnikovds@altarix.ru>
 *
 */
class ZendHttpClient implements HttpClientInterface
{
	private $request = null;
	private $response = null;
	
	public function __construct()
	{
		
	}
	
	public function post($url, $body = [], $headers = [])
	{
		$this->request = $this->createRequest(Request::METHOD_POST, $url, $body, $headers);
		$this->response = $this->sendRequest($this->request);
	}
	
	public function get($url, $body = [], $headers = [])
	{
		$this->request = $this->createRequest(Request::METHOD_GET, $url, $body, $headers);
		$this->response = $this->sendRequest($this->request);
	}
	
	public function getURL()
	{
		if (empty($this->request)) return null;
		return $this->request->getUriString();
	}
	
	public function responseHeaders()
	{
		if (empty($this->response)) return null;
		return $this->response->getHeaders()->toArray();
	}
	
	public function responseBodyRaw()
	{
		if (empty($this->response)) return null;
		return $this->response->getBody();
	}
	
	public function responseBodyAsJSON()
	{
		$rawBody = $this->responseBodyRaw();
		
		if (empty($rawBody)) return null;
		return json_decode($rawBody);
	}
	
	public function statusCode()
	{
		if (empty($this->response)) return false;
		return $this->response->getStatusCode();
	}
	
	/**
	 * Создает объект класса Request для выполнения запроса
	 * 
	 * @param string 	$method 	HTTP метод
	 * @param string 	$url 		URL запроса
	 * @param mixed 	$body 		Тело запроса
	 * @param array 	$headers 	Заголовки запроса
	 * @return \Zend\Http\Request
	 */
	private function createRequest($method, $url, $body, $headers)
	{
		$request = new Request();
		
		$request->setMethod($method)
		->setUri($url)
		->setContent($body);
		$request->setHeaders((new Headers())->addHeaders($headers));
		
		return $request;
	}
	
	/**
	 * Выполняет запрос и возвращает объект класса Response
	 * 
	 * @param Request $request
	 * @return \Zend\Http\Response
	 */
	private function sendRequest(Request $request)
	{
		return (new Client())->send($request);
	}	
}
