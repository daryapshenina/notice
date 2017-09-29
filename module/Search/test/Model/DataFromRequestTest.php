<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.06.17
 * Time: 11:30
 */

namespace Search\Controller;

use PHPUnit\Framework\TestCase;
use Search\Model\DataFromRequest;
use Zend\Http\Response;
use Zend\Http\Client;

class DataFromRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }


    /**
     *Проверка определения сервиса
     */
    public function testGetService()
    {
        $this->assertEquals('newfis',(DataFromRequest::getService('/newfis/drivers', 'newfis')));
        $this->assertEquals('driver',(DataFromRequest::getService('/search/driver?driver_license=7795456331', 'search')));
        $this->assertEquals('vehicleAll',(DataFromRequest::getService('/search/vehicleAll?request=97779577', 'search')));
        $this->assertEquals('specialTransport',(DataFromRequest::getService('/search/specialTransport?fixDate=11.09.2017&licPlateNum=A337477', 'search')));

    }


}