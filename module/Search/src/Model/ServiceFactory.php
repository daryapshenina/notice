<?php

namespace Search\Model;

class ServiceFactory
{

    public function __construct()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->restraintLogger = new RestraintLogger();
    }


    /**
     * Получение нужного сервиса
     * @return Fio|Fis|License|SpecialTransport|Taxi|Vehicle|Wanted
     * @throws \Exception
     */
    public function chooseService($serviceName)
    {
        switch ($serviceName) {
            case 'fio':
                $service = new Fio($this->responseLogger, $this->searchLogger, $this->serviceLogger, $this->restraintLogger);
                break;
            case 'license':
                $service = new License($this->responseLogger, $this->searchLogger, $this->serviceLogger, $this->restraintLogger);
                break;
            case 'vehicle':
                $service = new Vehicle($this->responseLogger, $this->searchLogger, $this->serviceLogger);
                break;
            case 'specialTransport':
                $service = new SpecialTransport($this->responseLogger, $this->searchLogger, $this->serviceLogger);
                break;
            case 'fis':
                $service = new Fis($this->responseLogger, $this->searchLogger, $this->serviceLogger, $this->restraintLogger);
                break;
            case 'taxi':
                $service = new Taxi($this->responseLogger, $this->searchLogger, $this->serviceLogger);
                break;
            case 'wanted':
                $service = new Wanted($this->responseLogger, $this->searchLogger, $this->serviceLogger);
                break;
            default:
                throw new \Exception('Сервис поиска ' . $serviceName . ' не предусмотрен');
        }
        return $service;
    }


    //ToDo не знаю, где это будет
    // /* Получение результата по фио, fis, wanted*/
  /*  public function getPerson()
    {

    }*/
/*получение результата по водительскому удостоверению, фио, fis, wanted */
  /*  public function getLicense()
    {

    }*/
}

