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
use Search\Model\Taxi;
use Search\Model\Vehicle;

class TaxiTest extends TestCase
{
    public $taxi;
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;

    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->taxi = new Taxi($this->responseLogger, $this->searchLogger, $this->serviceLogger);
        $this->taxi->spec = $this->getMockBuilder(SpecialTransport::class);
        $this->taxi->vehicle = $this->getMockBuilder(Vehicle::class);
        $this->taxi->vehicle->response = json_decode('{"responseCode":"OKДляТеста","responseComment":null,"errors":[],"vehicles":[{"plateNumber":"\u0410777\u0423\u041588","brand":"\u0412\u0410\u0417","model":"2106","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","ownerFirstName":"\u0427\u0410\u041b\u0411\u0423\u0428\u0415\u0412","ownerSecondName":"\u0410\u041b\u0415\u041a\u0421\u0415\u0419","ownerThirdName":"\u0412\u041b\u0410\u0414\u0418\u041c\u0418\u0420\u041e\u0412\u0418\u0427","ownerBirthday":"07-02-1969","vehicleYear":"1982","regionCode":"36","city":"\u0422\u041e\u041b\u042c\u042f\u0422\u0422\u0418","street":"\u041b\u0415\u041d\u0418\u041d\u0421\u041a\u0418\u0419 \u041f\u0420-\u0422","house":"24","corpus":"78","flat":"344","vehicleTypeCode":"2","bodyTypeCode":29,"vin":"\u0425\u0422\u0410210600\u04210644021","sts":"63\u0415\u0412592095","ogaitoName":"null","tiptcName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419","tipkuzName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419 \u041f\u0420\u041e\u0427\u0418\u0415"}],"wantedVehicles":[{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041c\u0415\u0420\u0421\u0415\u0414\u0415\u0421","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"88888888"},{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041a\u0418\u0410\u0420\u0418\u041e","color":"\u0424\u0418\u041e\u041b\u0415\u0422\u041e\u0412\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"917999"}],"wantedSP":[{"addedToWanted":1034712000000,"spSeries":"63\u0412\u0425","firstSPNumber":"288787","lastSPNumber":"288787","regionName":"\u0421\u0410\u041c\u0410\u0420\u0421\u041a\u0410\u042f \u041e\u0411\u041b.","initiatorRegionCode":"1136","initiatorPhone":"0123456789","spType":"20"}]}');
        parent::setUp();
    }

    /**
     * Проверка данных для поиску по ГРЗ
     */
    public function testCreateRequestVehicle()
    {
        $vehicleArray = array();
        $this->taxi->createRequestVehicle($vehicleArray);
        $this->assertArrayHasKey('request', $this->taxi->createRequestVehicle($vehicleArray));
    }

    /**
     * Проверка данных для поиску по разрешению на въезд
     */
    public function testCreateRequestSpec()
    {
        $requestArray = array('plate_number' => 'EM33377', 'ogai_code' => '650');
        $specArray = $this->taxi->createRequestSpec($requestArray);
        $this->assertEquals('EM33377', $specArray['licPlateNum']);
    }

    /**
     *
     */
    public function testExternalErrors()
    {
        $this->taxi->external_errors = true;
        $this->assertTrue($this->taxi->getExternalErrors());
    }

    /**
     * Проверка метода по получению данных из ответа vehicle
     */

    public function testCheckResponseData()
    {
        $response = '{"responseCode":"OK","responseComment":null,"errors":[],"vehicles":[{"plateNumber":"\u0410777\u0423\u041588","brand":"\u0412\u0410\u0417","model":"2106","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","ownerFirstName":"\u0427\u0410\u041b\u0411\u0423\u0428\u0415\u0412","ownerSecondName":"\u0410\u041b\u0415\u041a\u0421\u0415\u0419","ownerThirdName":"\u0412\u041b\u0410\u0414\u0418\u041c\u0418\u0420\u041e\u0412\u0418\u0427","ownerBirthday":"07-02-1969","vehicleYear":"1982","regionCode":"36","city":"\u0422\u041e\u041b\u042c\u042f\u0422\u0422\u0418","street":"\u041b\u0415\u041d\u0418\u041d\u0421\u041a\u0418\u0419 \u041f\u0420-\u0422","house":"24","corpus":"78","flat":"344","vehicleTypeCode":"2","bodyTypeCode":29,"vin":"\u0425\u0422\u0410210600\u04210644021","sts":"63\u0415\u0412592095","ogaitoName":"null","tiptcName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419","tipkuzName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419 \u041f\u0420\u041e\u0427\u0418\u0415"}],"wantedVehicles":[{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041c\u0415\u0420\u0421\u0415\u0414\u0415\u0421","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"88888888"},{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041a\u0418\u0410\u0420\u0418\u041e","color":"\u0424\u0418\u041e\u041b\u0415\u0422\u041e\u0412\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"917999"}],"wantedSP":[{"addedToWanted":1034712000000,"spSeries":"63\u0412\u0425","firstSPNumber":"288787","lastSPNumber":"288787","regionName":"\u0421\u0410\u041c\u0410\u0420\u0421\u041a\u0410\u042f \u041e\u0411\u041b.","initiatorRegionCode":"1136","initiatorPhone":"0123456789","spType":"20"}]}';
        $response = json_decode($response);
        $taxidata = $this->taxi->checkResponseData($response);
        $this->assertEquals('Имеется действующая лицензия', $taxidata->LicenseNum);
        $response = new \stdClass();
        $response->vehicles = array();
        $taxidata = $this->taxi->checkResponseData($response);
        $this->assertFalse($taxidata);
    }

    /**
     * Проверка метода по получению ошибки
     */
    public function testGetError()
    {
        $this->taxi->plate_number = 'А777УЕ88';
        $error = $this->taxi->getError();
        $this->assertEquals('Транспортное средство гос. номер ' . 'А777УЕ88' . ' не зарегистрировано в реестре такси.', $error->errorComment);
    }

    /**
     * Проверка метода для определения делать поиск по ГРЗ или нет
     */
    public function testGetVehicleData()
    {
        $this->taxi->plate_number = 'КУ37477';
        $info = new \stdClass();
        $spec = json_decode('{"category":[{"code":-888,"name":"\u041d\u0435 \u043e\u043f\u0440\u0435\u0434\u0435\u043b\u0435\u043d\u043e"}],"place":[{"code":-888,"name":"\u041d\u0435 \u043e\u043f\u0440\u0435\u0434\u0435\u043b\u0435\u043d\u043e"}]}');
        $this->assertFalse($this->taxi->getVehicleData($spec, array(), $info));
    }

    /**
     * Проверка метода для определения делать поиск по ГРЗ или нет
     */
    public function testGetVehicleDataTrue()
    {
        $info = new \stdClass();
        $spec = json_decode('{"category":[{"code":11,"name":"Грузовой транспорт"}],"place":[{"code":0,"name":"Выделенные полосы для общественного транспорта"}]}');
        $this->taxi->vehicle = $this->getMockBuilder(Vehicle::class)
            ->setMethods(['sendRequest'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->taxi->vehicle->response = json_decode('{"responseCode":"OKДляТеста","responseComment":null,"errors":[],"vehicles":[{"plateNumber":"\u0410777\u0423\u041588","brand":"\u0412\u0410\u0417","model":"2106","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","ownerFirstName":"\u0427\u0410\u041b\u0411\u0423\u0428\u0415\u0412","ownerSecondName":"\u0410\u041b\u0415\u041a\u0421\u0415\u0419","ownerThirdName":"\u0412\u041b\u0410\u0414\u0418\u041c\u0418\u0420\u041e\u0412\u0418\u0427","ownerBirthday":"07-02-1969","vehicleYear":"1982","regionCode":"36","city":"\u0422\u041e\u041b\u042c\u042f\u0422\u0422\u0418","street":"\u041b\u0415\u041d\u0418\u041d\u0421\u041a\u0418\u0419 \u041f\u0420-\u0422","house":"24","corpus":"78","flat":"344","vehicleTypeCode":"2","bodyTypeCode":29,"vin":"\u0425\u0422\u0410210600\u04210644021","sts":"63\u0415\u0412592095","ogaitoName":"null","tiptcName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419","tipkuzName":"\u041b\u0415\u0413\u041a\u041e\u0412\u041e\u0419 \u041f\u0420\u041e\u0427\u0418\u0415"}],"wantedVehicles":[{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041c\u0415\u0420\u0421\u0415\u0414\u0415\u0421","color":"\u041a\u0420\u0410\u0421\u041d\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"88888888"},{"prichRoz":"\u0423\u0413\u041e\u041b\u041e\u0412\u041d\u041e\u0415 \u0414\u0415\u041b\u041e:\u0423\u0413\u041e\u041d \u0422\u0421","dateOu":1166216400000,"regno":"\u0410777\u0423\u0415778","marka":"\u041a\u0418\u0410\u0420\u0418\u041e","color":"\u0424\u0418\u041e\u041b\u0415\u0422\u041e\u0412\u042b\u0419","vin":"\u0425\u0422\u0410210600\u04210644021","nmotor":"332900","nshasi":"77777","nkuzov":"0001001","uvd_ini":"\u041d\u041e\u0413\u0418\u041d\u0421\u041a\u041e\u0415 \u0423\u0412\u0414","phone_ini":"917999"}],"wantedSP":[{"addedToWanted":1034712000000,"spSeries":"63\u0412\u0425","firstSPNumber":"288787","lastSPNumber":"288787","regionName":"\u0421\u0410\u041c\u0410\u0420\u0421\u041a\u0410\u042f \u041e\u0411\u041b.","initiatorRegionCode":"1136","initiatorPhone":"0123456789","spType":"20"}]}');
        $this->assertTrue($this->taxi->getVehicleData($spec, array(), $info));
    }
}