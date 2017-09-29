<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.08.17
 * Time: 18:56
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;
use Search\Model\ServiceFactory;

class ServiceFactoryTest extends TestCase
{
    public function setUp()
    {
        $this->factory = new ServiceFactory();
        parent::setUp();
    }

    /**
     * Проверка исключения при неправильном названии сервиса
     */
    public function testChooseFailService()
    {

        try {
            $this->factory->chooseService('serviceName');
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Сервис поиска ' . 'serviceName' . ' не предусмотрен');
            return;
        }
        $this->fail('Ожидается exception некорректный сервис ');

    }

    /**
     * Проверка получения правильного объекта сервиса поиска
     */
    public function testChooseService()
    {

        $service = $this->factory->chooseService('wanted');
        $this->assertInstanceOf(Wanted::Class, $service);
        $service = $this->factory->chooseService('vehicleAll');
        $this->assertInstanceOf(Vehicle::Class, $service);
        $service = $this->factory->chooseService('newfis');
        $this->assertInstanceOf(Fis::Class, $service);

    }
}