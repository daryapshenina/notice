<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Logging\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Logging\Model\Fluent;

class LoggingController extends AbstractActionController
{
    public function saveAction()
    {
        $request = $this->getRequest();
        $postData = $this->prepareDataForFluent($request);
        $fluent = new Fluent();
        $fluent->post('mongo.http', $postData);
    }

    /**
     * Обработка полученного запроса
     * @param $request - полученный запрос
     * @return mixed - массив для записи в монгу
     * @throws \Exception
     */
    public function prepareDataForFluent($request)
    {
        $postData = $request->getContent();
        $postData = json_decode($postData,true);
        if (!($postData['imei']) or (!($postData['token'])) or (!($postData['service']))) {
            throw new \Exception("Не хватает данных для логирования");
        }
        if (((int)$postData['level'] < 1) or (((int)$postData['level'] > 5))) {
            throw new \Exception("Некорректный уровень логирования");
        }
        if (!($postData['level'])) {
            $postData['level'] = '5';
        }
        return $postData;
    }
}