<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace LoggingTest\Controller;

use Logging\Controller\LoggingController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class LoggingControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();
    }

    /**
     * Проверка с правильными данными и на получение исключения с неправильным уровнем логирования
     */
    public function testPrepareData()
    {
        $postData = [
            'imei' => '115274454',
            'level' => 4,
            'service' => 'authorization',
            'token' => '545',
        ];
        $controller = new LoggingController();
        $request = $controller->getRequest();
        $post = $request->getPost();
        $post->set('imei', '115274454');
        $post->set('level', '4');
        $post->set('service', 'authorization');
        $post->set('token', '545');
        $this->assertEquals($postData, $controller->prepareDataForFluent($request->setPost($post)));
        $post->set('level', '8');
        try {
            $controller->prepareDataForFluent($request->setPost($post));
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Некорректный уровень логирования');
            return;
        }
        $this->fail('Ожидается exception "неправильный уровень логирования"');
    }

    /**
     * Проверка получения exception при отсутствии imei и token
     */
    public function testPrepareException()
    {
        $controller = new LoggingController();
        $request = $controller->getRequest();
        $post = $request->getPost();
        $post->set('imei', '');
        $post->set('level', '');
        $post->set('service', '');
        $post->set('token', '');
        try {
            $controller->prepareDataForFluent($request->setPost($post));
        } catch (\Exception $e) {
            $this->assertEquals($e->getMessage(), 'Не хватает данных для логирования');
            return;
        }
        $this->fail('Ожидается exception "не хватает данных"');
    }
}
