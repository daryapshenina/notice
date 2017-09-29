<?php

namespace Mobileinspector\Basic;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Mobileinspector\Basic\ZendHttpClient;

/**
 * Тест обертки HTTP-клиента ZendHttpClient
 * 
 * Задача GIBDDPRSL-204 "Реализовать обёртку над http в mobileinspector-core"
 * 
 * @author SotnikovDS <sotnikovds@altarix.ru>
 *
 */
class HttpClientTest extends AbstractHttpControllerTestCase
{
	public function testCreation()
	{
		$client = new ZendHttpClient();
		$this->assertInstanceOf(ZendHttpClient::class, $client);
	}
	
	/**
	 * Проверка, что класс реализует интерфейс HttpClientInterface
	 */
	public function testInterfaceOf()
	{
		$client = new ZendHttpClient();
		$this->assertInstanceOf(HttpClientInterface::class, $client);
	}
}