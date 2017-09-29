<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authorization\Controller;

use Authorization\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Authorization\Model\Crypt;

class CryptModelTest extends AbstractHttpControllerTestCase
{
    /*
     * --testsuite AuthorizationCrypt
     * Проверка модели Crypt шифровки и расшифровки данных
     * */

    /* @var $crypt Crypt - Обьект */
    private $crypt;

    /* @var $testCase - Данные для тестирования */
    private $testCase = array(
        'hash' => '49kmJXJ3QhuZFJt09e5+N+9bmaL2/25gtKY2MYmiceU=',
        'str' => 'login'
    );

    /*
     * До прогонки
     * */
    public function setUp()
    {
        $this->crypt = new Crypt();
        parent::setUp();
    }

    /*
     * Проверка шифровки
     * */
    public function testEnCrypt(){
        $enCrypt = $this->crypt->enCrypt($this->testCase['str']);
        $this->assertContains($this->testCase['hash'],$enCrypt,'Не удалось зашифровать');
    }

    /*
     * Проверка расшифровки
     * */
    public function testDeCrypt(){
        $deCrypt = $this->crypt->deCrypt($this->testCase['hash']);
        $this->assertContains($this->testCase['str'],$deCrypt,'Не удалось расшифровать');
    }

}
