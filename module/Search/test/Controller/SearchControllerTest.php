<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SearchTest\Controller;

use Search\Controller\SearchController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class SearchControllerTest extends AbstractHttpControllerTestCase
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
     * Проверка правильного  route
     */
    public function testIndexRouteSearch()
    {
        $this->dispatch('/search/vehicleAll', 'GET');
        $this->assertModuleName('search');
        $this->assertControllerName(SearchController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SearchController');
        $this->assertMatchedRouteName('search');
    }

    /**
     * Проверка правильного  route
     */
    public function testIndexRouteFis()
    {
        $this->dispatch('/newfis/drivers', 'GET');
        $this->assertModuleName('search');
        $this->assertControllerName(SearchController::class); // as specified in router's controller name alias
        $this->assertControllerClass('SearchController');
        $this->assertMatchedRouteName('newfis');
    }


    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
