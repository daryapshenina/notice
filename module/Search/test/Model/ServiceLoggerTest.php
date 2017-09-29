<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:38
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;

class ServiceLoggerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Тест для подготовки массива данных для логирования
     */
    public function testPrepareLog()
    {
        $logger = new ServiceLogger();
        $serviceArray = $logger->prepareLog();
        $this->assertArrayHasKey('id_service', $serviceArray);
        $this->assertEquals('ServiceLogger', $serviceArray['id_service']);
        $this->assertArrayHasKey('url', $serviceArray);
        $this->assertArrayHasKey('code', $serviceArray);
        $this->assertArrayHasKey('original', $serviceArray);
    }
}