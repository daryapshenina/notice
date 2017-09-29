<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.08.17
 * Time: 18:48
 */

namespace Search\Controller;


use PHPUnit\Framework\TestCase;
use Search\Model\ImpulsMService;

class ImpulsMServiceTest extends TestCase
{
    public function setUp()
    {
        $this->ImpulsMService = new ImpulsMService();
        parent::setUp();
    }

    /**
     *ToDo тест будет, когда появится метод
     * Проверка метода на получение адреса из Кладр
     */
    public function testGetAddress()
    {
        $this->ImpulsMService->getAddress();
        $this->assertTrue($this->ImpulsMService->getAddress());
    }

    /**
     * Тест для транслитерации
     */
    public function testTranslit()
    {
        $this->assertEquals('77УЕ456331', $this->ImpulsMService->translit('77YЕ456331'));
    }


    /**
     * Тест для метода получения комментария об ошибке
     */
    public function testCheckResponse()
    {
        $this->ImpulsMService->response = json_decode('{"@type":"fisDriverSearchResponse","errors":[],"responseCode":"OK","drivers":[{"birthDate":"3112","birthPlace":"СОБИНКА","birthYear":"1966","city":"СОБИНКА","house":"176","id":"2103099265#1","name":"ГАЛИНА","nation":4404,"patronymic":"ВЛАДИМИРОВНА","region":"КРАСНОДАРСКИЙ КРАЙ","street":"РИМСКОГО-КОРСАКОВА","surname":"БЕЛОУСОВА","violations":[{"marka":"ВОЛЬВО","measures":[{"decisionDate":"2011-11-22T00:00:00","penaltyAmount":500,"postNumber":"23ДЯ101566","violationId":"2103099265#1"}],"model":"ХС60","regno":"К777ВР197","stotvCode":121511,"violationDate":"2011-11-22T00:00:00","violationId":"2103099265#1"}]}]}');
        $this->ImpulsMService->checkResponse('Проверьте введенные значения.');
        $this->assertEquals(null, $this->ImpulsMService->response->responseComment);
        $this->ImpulsMService->response = json_decode('{"@type":"FisDriverSearchResponse","responseCode":"ERROR","responseComment":"\u0418\u043d\u0444\u043e\u0440\u043c\u0430\u0446\u0438\u044f \u043e\u0442\u0441\u0443\u0442\u0441\u0442\u0432\u0443\u0435\u0442\n\n\n","errors":[],"drivers":[]}');
        $this->ImpulsMService->checkResponse('Проверьте введенные значения.');
        $this->assertEquals("Информация отсутствует\n\n\n", $this->ImpulsMService->response->responseComment);
    }
}