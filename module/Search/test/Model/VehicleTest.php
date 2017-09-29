<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:10
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;
use Search\Model\Vehicle;

class VehicleTest extends TestCase
{
    public $vehicle;
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;

    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->vehicle = new Vehicle($this->responseLogger, $this->searchLogger, $this->serviceLogger);
        parent::setUp();
    }

    /**
     * Получение запроса для ГРЗ
     */
    public function testVehiclePlateNumber()
    {
        $this->vehicle->vehicleData('K777BY197', 'licensePlateNumber');
        $this->assertEquals('Грз', $this->vehicle->searchLogger->type);
        $this->assertEquals('vehicle', $this->vehicle->url); //$this->search_string
        $this->assertEquals('К777ВУ197', $this->vehicle->search_string);
        $this->assertEquals('K777BY197', $this->vehicle->searchLogger->search_string);
    }

    /**
     * Получение запроса для спецпродукции
     */
    public function testVehicleEngineNumber()
    {
        $this->vehicle->vehicleData('6014475', 'engineNumber');
        $this->assertEquals('Спецпродукция «Номер двигателя»', $this->vehicle->searchLogger->type);
        $this->assertEquals('stealing', $this->vehicle->url); //$this->search_string
        $this->assertEquals('6014475', $this->vehicle->search_string);
    }
}