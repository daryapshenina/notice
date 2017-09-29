<?php

namespace Mobileinspector\Basic;

/**
 * Модель для форматирования данных для ответа клиенту в формате JSON.
 * JSON-ответ имеет следующую структуру:
 * {
 *     "responseCode": "OK" | "ERROR" -- код ответа,
 *     "responseMessage": String -- текст сообщения,
 *     "data": Array -- массив данных
 * }
 *
 * Задача GIBDDPRSL-100 "Разработать модель, форматирующую данные для формирования ответа клиенту"
 *
 * @author SotnikovDS <sotnikovds@altarix.ru>
 *
 */
class JsonClientResponse
{
	/**
	 * @var string Код ответа, ERROR или OK
	 */
	protected $responseCode;
	
	/**
	 * @var string Текст сообщения
	 */
	public $responseMessage;
	
	/**
	 * @var array Массив данных
	 */
	public $data;
	
	/**
	 * @param string 	$responseMessage 	Текст сообщения
	 * @param array 	$data 				Массив данных
	 * @param bool 		$error 				false -- responseCode будет "ERROR"; иначе -- responseCode будет "OK"
	 */
	public function __construct($responseMessage = '', $data = [], $error = false)
	{
		$this->responseMessage = $responseMessage;
		$this->data = $data;
		
		if ($error){
			$this->responseCode = 'ERROR';
		} else {
			$this->responseCode = 'OK';
		}
	}
	
	/**
	 * Возвращает строку в формате JSON
	 *
	 * @return string Сформированный JSON
	 */
	public function toString()
	{
		$jsonStuff = [
				"responseCode" 		=> $this->responseCode,
				"responseMessage" 	=> $this->responseMessage,
				"data" 				=> $this->data,
		];
		
		return json_encode($jsonStuff);
	}
}