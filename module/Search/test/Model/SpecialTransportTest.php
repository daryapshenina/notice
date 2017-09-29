<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:09
 */

namespace Search\Model;
use PHPUnit\Framework\TestCase;
use Search\Model\SpecialTransport;

class SpecialTransportTest extends TestCase
{
 public $specTransport;
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;

    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->specTransport = new SpecialTransport($this->responseLogger, $this->searchLogger, $this->serviceLogger);
        parent::setUp();
    }

    /**
     * Проверка обработки входного запроса
     */
    public function testCreateSoapRequest(){

       $specArray=array('fixDate'=>'11.08.2017','licPlateNum'=>'ky37477','ip'=>'10.0.3.15');
       $req= $this->specTransport->createSoapRequest($specArray);
        $request = new \stdClass();
        $request->records= new \stdClass();
        $request->records->rec = array("fixDate" => '2017-08-11T00:00:00', "id" => 1,
            "licPlateNum" => 'KY37477');
      $this->assertEquals($request,$req);
    }

    /**
     * Проверка метода для получения ответа
     */
    public function testGetResult(){
        $response=null;
        $this->specTransport->getResult($response);
        $this->assertEquals('Сервис поиска по спецтранспорту вернул некорректый ответ',$this->specTransport->comment);
    }

    /**
     * Проверка метода для получения зоны, в которой разрешено нахождение
     */
    public function testGetPlace(){
        $item=new \stdClass();
        $item->place ='4';
      $placeArray=$this->specTransport->getPlace($item);
       $this->assertEquals('Малая окружная железная дорога', $placeArray['name']);
    }

    /**
     * Проверка метода для получения категорий спец. транспорта
     */
    public function testGetCategory(){
        $item=new \stdClass();
        $item->category='6';
        $placeArray=$this->specTransport->getCategory($item);
       $this->assertEquals('Транспорт службы ЖКХ', $placeArray['name']);
    }
}