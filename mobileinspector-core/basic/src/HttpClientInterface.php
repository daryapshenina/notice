<?php
namespace Mobileinspector\Basic;

/**
 * Интерфейс обертки HTTP-клиента
 * 
 * Задача GIBDDPRSL-204 "Реализовать обёртку над http в mobileinspector-core"
 * 
 * @author SotnikovDS <sotnikovds@altarix.ru>
 *
 */
interface HttpClientInterface
{
	/**
	 * Осуществляет POST-запрос
	 * 
	 * @param string 	$url		URL сервиса
	 * @param unknown 	$body		Тело запроса
	 * @param array 	$headers	Заголовки запроса
	 */
	public function post($url, $body = null, $headers = null);
	
	/**
	 * Осуществляет GET-запрос
	 * 
	 * @param string 	$url		URL сервиса
	 * @param unknown 	$body		Тело запроса
	 * @param array 	$headers	Заголовки запроса
	 */
	public function get($url, $body = null, $headers = null);
	
	/**
	 * Возвращает URL запроса
	 * 
	 * @return string|NULL URL запроса
	 */
	public function getURL();
	
	/**
	 * Возвращает массив HTTP-заголовков ответа
	 * 
	 * @return array|NULL Массив HTTP-заголовков("название" => "значение")
	 */
	public function responseHeaders();
	
	/**
	 * Возвращает тело HTTP-ответа
	 * 
	 * @return string|NULL Тело ответа
	 */
	public function responseBodyRaw();
	
	/**
	 * Возвращает тело HTTP-ответа в виде JSON
	 *
	 * @return stdClass|NULL Тело ответа
	 */
	public function responseBodyAsJSON();
	
	/**
	 * Возвращает HTTP-код ответа
	 * 
	 * @return int|false Код ответа
	 */
	public function statusCode();
	
}