<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24.08.17
 * Time: 11:35
 */

namespace Search\Controller;


use PHPUnit\Framework\TestCase;
use Search\Model\RestraintLogger;

class AbstractServiceLogTest extends TestCase
{
    public $serviceLog;

    public function setUp()
    {
        $this->logger = new RestraintLogger();
        parent::setUp();
    }

    public function testGetUri(){
        $this->assertObjectHasAttribute('uri',$this->logger);
    }

    /**
     * Проверка на наличие необходимых данных для сервиса логирования
     */
    public function testSetBasicData()
    {
        $arrayLog = array();
        $this->assertArrayHasKey('imei', $this->logger->setBasicData($arrayLog, '2'));
        $this->assertArrayHasKey('token', $this->logger->setBasicData($arrayLog, '2'));
        $this->assertArrayHasKey('level', $this->logger->setBasicData($arrayLog, '2'));
        $this->assertArrayHasKey('service', $this->logger->setBasicData($arrayLog, '2'));
    }
}