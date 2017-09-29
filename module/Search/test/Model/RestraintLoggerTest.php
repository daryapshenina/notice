<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:38
 */

namespace Search\Controller;


use PHPUnit\Framework\TestCase;
use Search\Model\RestraintLogger;

class RestraintLoggerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Тест для подготовки массива данных для логирования
     */
    public function testPrepare()
    {
        $logger = new RestraintLogger();
        $logger->restraint = 'ПОДПИСКА О НЕВЫЕЗДЕ';
        $restraintArray = $logger->prepareLog();
        $this->assertArrayHasKey('restraint', $logger->prepareLog());
        $this->assertEquals('ПОДПИСКА О НЕВЫЕЗДЕ', $restraintArray['restraint']);
        $this->assertEquals('RestraintLogger', $restraintArray['id_service']);
    }

}