<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Authorization\Model\UserData;
use Authorization\Model\PostgreUserDataStorage;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Отдаёт по токену все данные юзера, кроме критических.
     * 
     * Передаваемые URL-параметры: 
     * 	token -- токен
     * 
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getUserDataAction()
    {
    	$isCritical = false;
    	$token = $this->params()->fromQuery('token');
    	
    	$storage = new UserData($token, (new PostgreUserDataStorage()));
    	$hashKey = $storage->getHashKey($isCritical);
    	$userData = $storage->find($hashKey);
    	
    	$isError = empty($userData);
    	$content = $this->createOutputJson($userData, $isError);
    	
    	$this->getResponse()->setContent($content);
    	return $this->getResponse();
    }
    
    /**
     * Отдаёт все данные юзера, включая критические (пароль).
     *
     * Передаваемые URL-параметры:
     * 	token -- токен
     *
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function getUserCriticalDataAction()
    {
    	$isCritical = true;
    	$token = $this->params()->fromQuery('token');
    	
    	$storage = new UserData($token, (new PostgreUserDataStorage()));
    	$hashKey = $storage->getHashKey($isCritical);
    	$userData = $storage->find($hashKey);
    	
    	$isError = empty($userData);
    	$content = $this->createOutputJson($userData, $isError);
    	
    	$this->getResponse()->setContent($content);
    	return $this->getResponse();
    }
    
    /**
     * Форматирует данные пользователя для ответа клиенту
     * 
     * @param 	array|\stdClass $userData 	Пользовательские данные
     * @param 	bool 			$isError	true -- если произошла ошибка авторизации; иначе -- false
     * @return 	string 						Сформированный JSON
     */
    private function createOutputJson($userData, $isError = false)
    {
    	if (empty($userData)) {
    		$userData = [];
    	}
    	
    	$out = [
    			'responseCode' => $isError ? 'ERROR' : 'OK',
    			'responseComment' => $isError ? 
    								'Ошибка авторизации. Пользователь не найден' :
    								'Пользователь найден',
    			'data' => $userData,
    	];
    	
    	return json_encode($out);
    }
}
