<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:37
 */

namespace Search\Controller;


use PHPUnit\Framework\TestCase;
use Search\Model\ResponseLogger;

class ResponseLoggerTest extends TestCase
{
    public function setUp()
    {
        $this->logger = new ResponseLogger();
        parent::setUp();
    }

    public function testPrerareLog()
    {
        $this->logger->request = '{"client":{"username":"TESTQA6","password":"Abc_123","ogaiCode":"650","ip":"11.0.0.1","schema":"GIBDD_APR"},"name":"\u041f\u0410\u0412\u0415\u041b","surname":"\u041a\u041e\u0422\u041e\u041b\u042e\u0411\u041e\u0412","year":"1988","patronymic":"\u041c\u0418\u0425\u0410\u0419\u041b\u041e\u0412\u0418\u0427","subdate":"3008"}';
        $this->logger->response = '{"responseCode":"OK","responseComment":null,"errors":[],"drivers":[{"surname":"ЧАЛБУШЕВ","name":"АЛЕКСЕЙ","patronymic":"ВЛАДИМИРОВИЧ","birthYear":"1970","birthDate":"0702","region":"САМАРСКАЯ ОБЛАСТЬ","city":"ТОЛЬЯТТИ Г.","street":"ЛЕНИНСКИЙ ПР-Т","house":"24","corpus":null,"flat":"344","nation":null,"gender":"1","birthPlaceCode":"1118","birthPlace":"ВОЛГОГРАДСКАЯ ОБЛАСТЬ","phone":null,"violations":[],"wanted":[],"wantedSP":[],"documents":[]}],"photo":null}';
        $arraylog = $this->logger->prepareLog();
        $this->assertEquals('ResponseLogger', $arraylog['id_service']);
    }

    /**
     * Проверка метода удаления из данных запроса пароля пользователя
     */
    public function testDeletePassword()
    {
        $this->logger->request = '{"client":{"username":"TESTQA6","password":"Abc_123","ogaiCode":"650","ip":"11.0.0.1","schema":"GIBDD_APR"},"name":"\u041f\u0410\u0412\u0415\u041b","surname":"\u041a\u041e\u0422\u041e\u041b\u042e\u0411\u041e\u0412","year":"1988","patronymic":"\u041c\u0418\u0425\u0410\u0419\u041b\u041e\u0412\u0418\u0427","subdate":"3008"}';
        $request = json_decode($this->logger->deletePassword(), true);
        $this->assertEquals('------', $request['client']['password']);

    }

}