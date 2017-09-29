<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authorization\Controller;

use Authorization\Model\PGTokenStorage;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Authorization\Model\Token;


class TokenTest extends AbstractHttpControllerTestCase
{
    public $config;

    /* @var $tokenObject Token - Обьект */
    private $token;

    /* @var $hash string токен */
    private static $hash;

    public function setUp()
    {
        $this->config = require __DIR__ . '/../../config/module.config.php';

        $this->token = new Token(new PGTokenStorage($this->config['doctrine']['connection']['orm_default']['params']));
    }

    /*
     * Сгенерировать токен
     * */
    public function testSetToken()
    {
        $result = $this->token->generateToken();
        isset($result['token']) ? self::$hash = $result['token'] : false;
        $this->assertNotEmpty($result['insert_id'], "Токен не был сгенерирован");
    }

    /**
     * Проверить токен в базе
     * */
    public function testCheckToken()
    {
        $result = $this->token->checkToken(self::$hash);
        $this->AssertEquals($result[0]->getToken(), self::$hash, "Токен не существует");
    }

    /*
     * Удалить токен
     * */
    public function testDeleteToken()
    {
        $result = $this->token->deleteToken(self::$hash);
        $this->AssertEquals($result, 1, "Ошибка при удалении");
    }
}