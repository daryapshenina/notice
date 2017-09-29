<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Search\Controller;

use Search\Model\SearchResult;
use Search\Model\ServiceFactory;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Search\Model\UserInfo;
use Search\Model\DataFromRequest;

class SearchController extends AbstractActionController
{
    /**
     *
     */
    public function fioAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('fio');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestES = $service->createRequest($request, $userInfo);
        $service->search($requestES);
        $service->getResponse();
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
        exit;
    }

    /**
     *
     */
    public function newfisAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('fis');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestES = $service->createRequest($request, $userInfo);
        $service->search($requestES);
        $service->getResponse();
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
        exit;
    }

    /**
     *
     */
    public function licenseAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('license');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestES = $service->createRequest();
        $service->search($requestES);
        $service->getResponse($request);
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
        exit;
    }

    /**
     *
     */
    public function specialTransportAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('specialTransport');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestSpecTr = $service->createRequest();
        $service->search($requestSpecTr);
        $service->getResponse($request);
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
        exit;
    }

    /**
     *
     */
    public function vehicleAllAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('vehicle');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);

        $params = array(
            "licensePlateNumber", "sts", "pts", "vin", "chassisNumber", "bodyNumber", "engineNumber"
        );
        $types = array('wantedVehicles', 'vehicles', 'wantedSP');
        $out = array('wantedVehicles' => array(), 'vehicles' => array(), 'wantedSP' => array());
        foreach ($params as $param) {

            $service->setType($param);
            $requestES = $service->createRequest();
            $service->search($requestES);
            $this->getResponse();
            foreach ($types as $type) {
                if (!empty($service->response->{$type})) {

                    foreach ($service->response->{$type} as $item) {

                        if (is_object($item) and count(get_object_vars($item)) > 0) {

                            $item->source = $param;
                            $out[$type][] = $item;

                        }

                    }
                }
            }
        }
        $service->result = $out;
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
    }

    /**
     *
     */
    public function wantedAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('wanted');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestSpecTr = $service->createRequest();
        $service->search($requestSpecTr);
        $service->getResponse($request);
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
   }

    /**
     *
     */
    public function taxiAction()
    {
        $request = $this->params()->fromQuery();
        $token = 'token';
        $userInfo = new UserInfo();
        $userInfo->setInfo($token);
        $factory = new ServiceFactory();
        $service = $factory->chooseService('taxi');
        $service->setRequest($request);
        $service->setUserInfo($userInfo);
        $requestSpecTr = $service->createRequest();
        $service->search($requestSpecTr);
        $service->getResponse($request);
        $searchRes = new SearchResult();
        echo($searchRes->getServiceResponse($service));
    }
}
