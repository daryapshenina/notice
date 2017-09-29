<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:10
 */

namespace Search\Model;


use PHPUnit\Framework\TestCase;
use Search\Model\Wanted;

class WantedTest extends TestCase
{
    public $want;
    public $responseLogger;
    public $searchLogger;
    public $serviceLogger;

    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->want = new Wanted($this->responseLogger, $this->searchLogger, $this->serviceLogger);
        parent::setUp();
    }

    /**
     * Проверка обработки входного запроса
     */

    public function testCreatedWantedRequest()
    {
        $requestArray = array('surname' => 'котолюбов', 'name' => 'павел', 'year' => '1988', 'day' => '01', 'rank' => 'inspector');
        $request = $this->want->createRequest($requestArray);
        $this->assertEquals("котолюбов павел 1988", $this->want->searchLogger->search_string);
        $wantedRequest = new \stdClass();
        $wantedRequest->surname = 'КОТОЛЮБОВ';
        $wantedRequest->name = 'ПАВЕЛ';
        $wantedRequest->year = '1988';
        $this->assertEquals($wantedRequest, $request);
    }

    /**
     * Проверка метода, определяющего находится ли человек в поиске
     */
    public function testGetWanted()
    {
        $this->want->response = json_decode('{"responseCode":"OK","responseComment":"По вашему запросу найдено 1 лиц в розыске.","errors":[],"wanted":[{"surname":"ЧАЛБУШЕВ","name":"АЛЕКСЕЙ","patronymic":"ВЛАДИМИРОВИЧ","birthDate":"1969-02-07T00:00:00.000+0300","republic":"РЕСПУБЛИКА АРМЕНИЯ","region":null,"district":"АРМАВИРСКИЙ Р-Н","city":"САРДАРАПАТ","passport":null,"wantedRepublic":"РЕСПУБЛИКА АРМЕНИЯ","wantedRegion":null,"wantedDistrict":null,"wantedCity":"Г.ЕРЕВАН","article":"СТ.327 Ч.1","criminalCaseNumber":"48104214","criminalCaseDate":"2014-02-27T00:00:00.000+0400","num_rd":"48404314","uvd":"ПОЛИЦИЯ РЕСПУБЛИКИ АРМЕНИЯ","rovd":"АРМАВИРКСКИЙ ОП","restraint":"АРЕСТ","num_cirk_sr":null,"mvd_ust":null,"date_ust":null,"snroz_name":null,"ustroz_name":null,"ustpomroz_name":null,"ustmestroz_name":null}]}');
        $this->want->getWanted();
        $this->assertTrue($this->want->searchLogger->wanted);
    }


    /**
     * Проверка метода для получения результата сервиса
     */
    public function testGetResult()
    {
        $this->want->response = json_decode('{"responseCode":"OK","responseComment":"По вашему запросу найдено 1 лиц в розыске.","errors":[],"wanted":[{"surname":"ЧАЛБУШЕВ","name":"АЛЕКСЕЙ","patronymic":"ВЛАДИМИРОВИЧ","birthDate":"1969-02-07T00:00:00.000+0300","republic":"РЕСПУБЛИКА АРМЕНИЯ","region":null,"district":"АРМАВИРСКИЙ Р-Н","city":"САРДАРАПАТ","passport":null,"wantedRepublic":"РЕСПУБЛИКА АРМЕНИЯ","wantedRegion":null,"wantedDistrict":null,"wantedCity":"Г.ЕРЕВАН","article":"СТ.327 Ч.1","criminalCaseNumber":"48104214","criminalCaseDate":"2014-02-27T00:00:00.000+0400","num_rd":"48404314","uvd":"ПОЛИЦИЯ РЕСПУБЛИКИ АРМЕНИЯ","rovd":"АРМАВИРКСКИЙ ОП","restraint":"АРЕСТ","num_cirk_sr":null,"mvd_ust":null,"date_ust":null,"snroz_name":null,"ustroz_name":null,"ustpomroz_name":null,"ustmestroz_name":null}]}');
        $this->want->getResult();
        $this->assertEquals('По вашему запросу найдено 1 лиц в розыске.', $this->want->comment);
    }
}