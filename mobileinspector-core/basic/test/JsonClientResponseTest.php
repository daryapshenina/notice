<?php

namespace Mobileinspector\Basic;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Mobileinspector\Basic\ZendHttpClient;

/**
 * Тестирование модели форматирования данных для ответа клиенту
 * 
 * @author SotnikovDS <sotnikovds@altarix.ru>
 *
 */
class JsonClientResponseTest extends AbstractHttpControllerTestCase
{
	private $testResponseMessage = 'something response message';
	private $testDataArray;
	
	protected function setUp()
	{
		$testDataArray = ['some', 'data', 'array'];
		$this->testDataArray = $testDataArray;	
	}
	
	/**
	 * Создание объекта
	 */
	public function testCreation()
	{
		$client = new JsonClientResponse();
		$this->assertInstanceOf(JsonClientResponse::class, $client);
	}
	
	/**
	 * Получение JSON'а, передавая только responseMessage
	 */
	public function testJsonCreationWithoutData()
	{
		$client = new JsonClientResponse($this->testResponseMessage);
		$jsonFromNativeFunction = json_encode($client);
		$jsonFromClassMethod = $client->toString();
		
		$this->assertEquals('{"responseCode":"OK","responseMessage":"something response message","data":[]}', $jsonFromNativeFunction);
		$this->assertEquals($jsonFromNativeFunction, $jsonFromClassMethod);
	}
	
	/**
	 * Получение JSON'а, передавая только responseMessage и data
	 */
	public function testJsonCreationWithDataFromDataArray()
	{
		$client = new JsonClientResponse($this->testResponseMessage, $this->testDataArray);
		$jsonFromNativeFunction = json_encode($client);
		$jsonFromClassMethod = $client->toString();
		
		$this->assertEquals('{"responseCode":"OK","responseMessage":"something response message","data":["some","data","array"]}', $jsonFromNativeFunction);
		$this->assertEquals($jsonFromNativeFunction, $jsonFromClassMethod);
	}
	
	/**
	 * Получение JSON'а, передавая responseMessage, data и error
	 */
	public function testJsonCreationWithError()
	{
		$client = new JsonClientResponse($this->testResponseMessage, $this->testDataArray, true);
		$jsonFromNativeFunction = json_encode($client);
		$jsonFromClassMethod = $client->toString();
		
		$this->assertEquals('{"responseCode":"ERROR","responseMessage":"something response message","data":["some","data","array"]}', $jsonFromNativeFunction);
		$this->assertEquals($jsonFromNativeFunction, $jsonFromClassMethod);
	}
	
	/**
	 * Получение JSON'а, ничего не указывая
	 */
	public function testJsonCreationWithoutEverything()
	{
		$client = new JsonClientResponse();
		$jsonFromNativeFunction = json_encode($client);
		$jsonFromClassMethod = $client->toString();
		
		$this->assertEquals('{"responseCode":"OK","responseMessage":"","data":[]}', $jsonFromNativeFunction);
		$this->assertEquals($jsonFromNativeFunction, $jsonFromClassMethod);
	}
}