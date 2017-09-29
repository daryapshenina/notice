<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authorization\Controller;

use Authorization\Entity\UserDataModel;
use Authorization\Model\Crypt;
use Authorization\Model\UserData;

use Authorization\Model\PostgreUserDataStorage;

use PHPUnit\Framework\Constraint\Exception;
use SebastianBergmann\CodeCoverage\Report\PHP;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UserDataTest extends AbstractHttpControllerTestCase
{
    /* @var $storage UserData хранилище */
    public $storage;

    /* @var $crypt Crypt */
    private $crypt;

    /* @var $testToken string тестовый токен */
    private $testToken = 'testToken1';

    /* @var $data array данные хранимые в ходе выполнения теста */
    private static $data;

    /* @var $userData array данные заполняющиеся из $testCase */
    private static $userData;

    /* @var $testHashKey string ключь который должен будит сгенерироватся если тестовый токен равен testToken1*/
    private static $testHashKey = '87ab47ceae0b4bab9bd3b440a76c6345';

    /* @var $testHashKeyForCritical string ключь который должен будит сгенерироватся для критических джанных если тестовый токен равен testToken1*/
    private static $testHashKeyForCritical = '9c25aa7616b05030da6874db83c32d7b';

    private $testCase = array(
        'login' => 'viktor',
        'pwd' => 'qwerty',
        'ip' => '127.0.0.1',
        'fio' => 'Хованский Юрий Александрович'
    );

    public function setUp()
    {
        $this->crypt = new Crypt();
        $this->storage = new UserData($this->testToken, (new PostgreUserDataStorage()));
        self::$userData = array();

        foreach ($this->testCase as $key => $value) {
            self::$userData[$key] = $value;
        }
    }

    /*
     * Тест на шифрование данных
     * */
    public function testCode()
    {
        $data = $this->storage->prepareSave($this->testCase, $this->storage->getHashKey(false));
        $datCritical = $this->storage->prepareSave($this->testCase, $this->storage->getHashKey(true));

        $encodedData['data'] = $data;
        $encodedData['dataCritical'] = $datCritical;

        self::$data = $encodedData;

        $this->assertNotEmpty(self::$data['data'], "Не смог закодировать данные");
        $this->assertNotEmpty(self::$data['dataCritical'], "Не смог закодировать данные Critical");
    }

    /*
     * Тест на расшифровку
     * */
    public function testDecode()
    {
        $data = $this->storage->afterGet(self::$data['data'], $this->storage->getHashKey(false));
        $datCritical = $this->storage->afterGet(self::$data['dataCritical'], $this->storage->getHashKey(true));

        if ($this->testCase == $data) {
            $data = true;
        }
        if ($this->testCase == $datCritical) {
            $datCritical = true;
        }

        $this->assertNotEmpty($data, "Не смог разкодировать данные");
        $this->assertNotEmpty($datCritical, "Не смог разкодировать данные Critical");
    }

    /*
     * Тест на сохранение критических данных
     * */
    public function testSaveCritical()
    {
        $result = $this->storage->save($this->testCase, true);

        $this->assertNotEmpty($result, "Не удалось сохранить Critical");
    }

    /*
     * Тест на сохранение данных
     * */
    public function testSave()
    {
        $result = $this->storage->save($this->testCase);

        $this->assertNotEmpty($result, "Не удалось сохранить");
    }

    /*
     * Поиск данных по ключю
     * */
    public function testFind()
    {
        $result = $this->storage->find(self::$testHashKey);
        $this->assertNotEmpty($result, "Данные не найдены");
    }

    /*
     * Поиск критических данных по ключю
     * */
    public function testFindCritical()
    {
        $result = $this->storage->find(self::$testHashKeyForCritical);
        $this->assertNotEmpty($result, "Данные critical не найдены");
    }

}