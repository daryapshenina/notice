<?php

namespace ApplicationTest\Model;

use Authorization\Model\AbstractUserDataStorage;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class PostgreUserDataStorageTest extends AbstractHttpControllerTestCase
{
	private static $testKey;
	private static $testValue;
	
	public static function setUpBeforeClass()
	{
		self::$testKey = self::generateRandomString(10);
		self::$testValue = self::generateRandomString(20);
	}
	
	public function testCreation()
	{
		$storage = AbstractUserDataStorage::create();
		$this->assertNotNull($storage);
	}
	
	public function testCreationPGSql()
	{
		$storage = AbstractUserDataStorage::create(AbstractUserDataStorage::POSTGRE_SQL);
		$this->assertNotNull($storage);
	}
		
	public function testGetValue()
	{
		$storage = AbstractUserDataStorage::create();
		
		$storage->set(self::$testKey, self::$testValue);
		$value = $storage->get(self::$testKey);
		
		$this->assertEquals($value, self::$testValue);
	}
	
	private static function generateRandomString($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		
		return $randomString;
	}
}