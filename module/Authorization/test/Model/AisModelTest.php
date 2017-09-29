<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.06.17
 * Time: 11:30
 */

namespace Authorization\Controller;

use PHPUnit\Framework\TestCase;
use Authorization\Model\Ais;
use Zend\Http\Response;
use Zend\Http\Client;

class AisModelTest extends TestCase
{
    private $ais;
    private $client;
    private $content;


    public function setUp()
    {
        $this->ais = new Ais('login', 'password', '45650');
        $this->ais->client = $this->getMockBuilder(Client::class)
            ->setMethods(['send', 'getBody'])
            ->getMock();
        $this->content = new Response();
        parent::setUp();
    }

    /**
     * Проверка прохождения авторизации
     */
    public function testAuthorization()
    {
        $this->content->setContent('{"tgt":"TGT-2491-d3PRAMHTWnMACIyfvamDE73Jqa9kU4EWNA3UEyI45vv2qdmjOB-security.localdomain","code":"ok","message":"Запрос успешно обработан: TGT сформирован"}');
        $this->ais->client->expects($this->any())
            ->method('send')
            ->willReturn($this->content);
        $response = $this->ais->checkAuthorization();
        $this->assertEquals("TGT-2491-d3PRAMHTWnMACIyfvamDE73Jqa9kU4EWNA3UEyI45vv2qdmjOB-security.localdomain", $response);
    }


    /**
     * Проверка прохождения авторизации при получения ошибки "badCredentials"
     */
    public function testAuthorizationbadCredentials()
    {
        $this->content->setContent('{"tgt":null,"code":"badCredentials","message":"Введены неверные логин или пароль"}');
        $this->ais->client->expects($this->any())
            ->method('send')
            ->willReturn($this->content);
        try {
            $this->ais->checkAuthorization();
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Некорректный пароль или пользователь с указанным логином не зарегистрирован в СУДИС МВД России.');
            return;
        }
        $this->fail('Ожидается exception некорректный пароль ');
    }

    /**
     * Проверка прохождения авторизации при получения ошибки "internalError"
     */
    public function testAnalizeTgtAnswerInternal()
    {
        $this->content->setContent('{"tgt":null,"code":"internalError","message":"Введены неверные логин или пароль"}');
        $this->ais->client->expects($this->any())
            ->method('send')
            ->willReturn($this->content);
        try {
            $this->ais->checkAuthorization();
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Сервис аутентификации СУДИС МВД России не доступен или вернул ошибку взаимодействия.');
            return;
        }
        $this->fail('Ожидается exception некорректный пароль ');
    }

    /**
     * Проверка получения департамента
     */
    public function testCheckDepartment()
    {

        $content = new Response();
        $content->setContent('[{"id":37992771,"code":"45000","name":"Управление ГИБДД по г.Москве"},{"id":37992471,"code":"45650","name":"ОБ по ЮАО"},{"id":57032520,"code":"46608","name":"Тестовое подразделение МИ"}]');
        $this->ais->client->expects($this->any())
            ->method('send')
            ->willReturn($content);
        $this->assertTrue($this->ais->checkDepartment($this->client));
        $this->ais->ogai_code = '45001';
        $this->assertFalse($this->ais->checkDepartment($this->client));
    }

    /**
     * Возвращает массив из xml для проверки обработки xml
     * @return array
     */
    public function getXml()
    {
        $arrayOfXml = array();
        $trueXml=file_get_contents('./module/Authorization/test/Data/trueAisXml.xml');
        $falseXml=file_get_contents('./module/Authorization/test/Data/falseAisXml.xml');
        $arrayOfXml[] = array($trueXml, $falseXml);
        return $arrayOfXml;
    }

    /**
     * @dataProvider getXml
     */

    public function testGetUser($trueXml, $falseXml)
    {
        $this->assertTrue($this->ais->getUser($trueXml));
        $this->assertFalse($this->ais->getUser($falseXml));
    }

    /**
     * Проверка URL-кодирования пароля
     */
    public function testPreparePassword()
    {
        $password = 'Abc_123 ';
        $this->assertEquals('Abc_123', $this->ais->PreparePassword($password));
        $password = 'Test123@';
        $this->assertEquals('Test123%40', $this->ais->PreparePassword($password));
    }

}