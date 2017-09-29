<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:37
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;
use Search\Model\SearchLogger;

class SearchLoggerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testPrepareLog(){
        $logger = new SearchLogger();
        $searchArray=$logger->prepareLog();
        $this->assertEquals('SearchLogger', $searchArray['id_service']);
    }
}