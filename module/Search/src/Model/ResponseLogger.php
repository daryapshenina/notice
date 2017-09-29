<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:31
 */

namespace Search\Model;

use Zend\Http\Client;

//ServiceLogWriter
class ResponseLogger extends AbstractServiceLog
{
    public $request;
    public $response;
    public $userInfo;

    /**
     * Подготовка данных для логирования
     * @param $request
     * @param $response
     * @param $basicLogArray
     * @return mixed
     */
    public function prepareLog()
    {
        $request = $this->deletePassword();
        $level = ''/*Какой-то метод для назначения уровня логирования*/
        ;
        //$basicLogArray['level']=$level;
        $basicLogArray = array();
        $basicLogArray['request'] = $request;
        $basicLogArray['id_service'] = 'ResponseLogger';
        $basicLogArray['response'] = $this->response;
        $basicLogArray['createDate'] = date('Y-m-d H:s:i', time());
        return $basicLogArray;
    }

    /**
     * Удаление пароля из данных пользователя
     * @param $request
     * @return mixed|string
     */
    public function deletePassword()
    {

        $request = json_decode($this->request, true);
        if (isset($request['client'])) {
            $request['client'] = (array)$request['client'];
            $request['client']['password'] = '------';
        }
        $request = json_encode($request);
        return $request;
    }

    /**
     * Отправка данных в сервис логирования
     * @param $request
     * @param $response
     * @param $basicLogArray
     */
    public function send()
    {
        $arrayLog = $this->prepareLog();
        $logLevel = 2;
        $this->saveLog($arrayLog, $logLevel);
    }
}