<?php

namespace Search\Model;

class RestraintLogger extends AbstractServiceLog
{
    public $restraint;
    public $num_cirk_sr;
    public $date_ust;

    /**
     * Подготовка данных для логирования
     * @param $request
     * @param $response
     * @param $basicLogArray
     * @return mixed
     */
    public function prepareLog()
    {
        $basicLogArray = array();
        $basicLogArray['id_service'] = 'RestraintLogger';
        $basicLogArray['createDate'] = date('Y-m-d H:s:i', time());
        $basicLogArray['restraint'] = $this->restraint;
        $basicLogArray['num_cirk_sr'] = $this->num_cirk_sr;
        $basicLogArray['date_ust'] = $this->date_ust;
        return $basicLogArray;
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