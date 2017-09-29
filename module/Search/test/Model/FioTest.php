<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:08
 */

namespace Search\Model;

use PHPUnit\Framework\TestCase;
use Search\Model\Fio;

class FioTest extends TestCase
{
    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->restraintLogger = new RestraintLogger();
        $this->fio = new Fio($this->responseLogger, $this->searchLogger, $this->serviceLogger, $this->restraintLogger);
        $this->fio->response = json_decode('{"responseCode":"OK","responseComment":null,"errors":[],"drivers":[{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthYear":"1975","birthDate":"2505","region":"ОРЕНБУРГСКАЯ ОБЛ.","city":"ОРЕНБУРГ","street":"КВАРТАЛ N15 ПОДМАЯЧНОГО ПОСЕЛКА","house":"15","corpus":null,"flat":null,"nation":"2357","gender":"1","birthPlaceCode":"2357","birthPlace":"КИРГИЗИЯ (КЫРГЫЗСТАН)","phone":null,"violations":[{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T23:02:54.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":12143,"pddCode":80300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731497","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731497","penaltyAmount":500,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-31T00:00:00.000+0300","violationInputDate":"2015-10-31T04:09:28.000+0300","ogaiName":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве","ogaiCode":"506","stotvCode":121201,"pddCode":61300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277155060521487","decisionDate":"2015-10-31T00:00:00.000+0300","decision":"Штраф Пост.18810277155060521487","penaltyAmount":1000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-11T00:00:00.000+0300","priz2025":null,"division":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"СМОЛЕНСКАЯ ПЛ.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T22:02:44.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":148,"pddCode":220900,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731721","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731721","penaltyAmount":3000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":"27","building":null,"corpus":null,"kilometer":null,"meter":null}],"wanted":[{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthDate":"1975-05-25T00:00:00.000+0300","republic":"КИРГССР","region":"ДЖАЛАЛ-АБАДСКАЯ ОБЛ.","district":"СУЗАКСКИЙ Р-Н","city":"С.СУЗАК","passport":"5311 067299","wantedRepublic":"РФ","wantedRegion":"САМАРСКАЯ ОБЛ.","wantedDistrict":null,"wantedCity":"Г.САМАРА","article":"СТ.327 Ч.3","criminalCaseNumber":"10094","criminalCaseDate":"2014-01-22T00:00:00.000+0400","num_rd":"478","uvd":"ГУ МВД РОССИИ ПО САМАРСКОЙ ОБЛ.","rovd":"ОП №4 (ОКТЯБРЬСКИЙ)","restraint":"ПОДПИСКА О НЕВЫЕЗДЕ","num_cirk_sr":null,"mvd_ust":null,"date_ust":null,"snroz_name":null,"ustroz_name":null,"ustpomroz_name":null,"ustmestroz_name":null}],"wantedSP":[],"documents":[]},{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthYear":"1975","birthDate":"2505","region":"МОСКВА Г.","city":null,"street":"ПАРКОВАЯ 15-Я","house":"54","corpus":null,"flat":"42","nation":"2357","gender":"1","birthPlaceCode":"2357","birthPlace":"КЫРГЫЗСТАН","phone":null,"violations":[{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T23:02:54.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":12143,"pddCode":80300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731497","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731497","penaltyAmount":500,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-31T00:00:00.000+0300","violationInputDate":"2015-10-31T04:09:28.000+0300","ogaiName":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве","ogaiCode":"506","stotvCode":121201,"pddCode":61300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277155060521487","decisionDate":"2015-10-31T00:00:00.000+0300","decision":"Штраф Пост.18810277155060521487","penaltyAmount":1000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-11T00:00:00.000+0300","priz2025":null,"division":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"СМОЛЕНСКАЯ ПЛ.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T22:02:44.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":148,"pddCode":220900,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731721","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731721","penaltyAmount":3000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":"27","building":null,"corpus":null,"kilometer":null,"meter":null}],"wanted":[{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthDate":"1975-05-25T00:00:00.000+0300","republic":"КИРГССР","region":"ДЖАЛАЛ-АБАДСКАЯ ОБЛ.","district":"СУЗАКСКИЙ Р-Н","city":"С.СУЗАК","passport":"5311 067299","wantedRepublic":"РФ","wantedRegion":"САМАРСКАЯ ОБЛ.","wantedDistrict":null,"wantedCity":"Г.САМАРА","article":"СТ.327 Ч.3","criminalCaseNumber":"10094","criminalCaseDate":"2014-01-22T00:00:00.000+0400","num_rd":"478","uvd":"ГУ МВД РОССИИ ПО САМАРСКОЙ ОБЛ.","rovd":"ОП №4 (ОКТЯБРЬСКИЙ)","restraint":"ПОДПИСКА О НЕВЫЕЗДЕ","num_cirk_sr":null,"mvd_ust":null,"date_ust":null,"snroz_name":null,"ustroz_name":null,"ustpomroz_name":null,"ustmestroz_name":null}],"wantedSP":[],"documents":[]},{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthYear":"1975","birthDate":"2505","region":"МОСКВА Г.","city":null,"street":"ПАРКОВАЯ 15-Я","house":"54","corpus":null,"flat":"42","nation":"2357","gender":"1","birthPlaceCode":"2357","birthPlace":"КИРГИЗИЯ (КЫРГЫЗСТАН)","phone":null,"violations":[{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T23:02:54.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":12143,"pddCode":80300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731497","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731497","penaltyAmount":500,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-31T00:00:00.000+0300","violationInputDate":"2015-10-31T04:09:28.000+0300","ogaiName":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве","ogaiCode":"506","stotvCode":121201,"pddCode":61300,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277155060521487","decisionDate":"2015-10-31T00:00:00.000+0300","decision":"Штраф Пост.18810277155060521487","penaltyAmount":1000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-11T00:00:00.000+0300","priz2025":null,"division":"6 СБ ДПС ГИБДД на спецтрассе ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"СМОЛЕНСКАЯ ПЛ.","crossStreet":null,"house":null,"building":null,"corpus":null,"kilometer":null,"meter":null},{"violationDate":"2015-10-30T00:00:00.000+0300","violationInputDate":"2015-10-30T22:02:44.000+0300","ogaiName":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве","ogaiCode":"650","stotvCode":148,"pddCode":220900,"regno":"КУ53177","marka":"ШКОДА","model":"ОКТАВИЯ","measures":[{"postNumber":"18810277156501731721","decisionDate":"2015-10-30T00:00:00.000+0300","decision":"Штраф Пост.18810277156501731721","penaltyAmount":3000,"deprivationDays":null,"deprivationMonths":null,"deprivationBeginDate":null,"divestmentEndDate":null,"arestInDays":null,"arestBeginDate":null,"arestEndDate":null,"decisionExecuteDate":null,"decisionEntryDate":"2015-11-10T00:00:00.000+0300","priz2025":null,"division":"ОБ ДПС ГИБДД УВД по ЮАО ГУ МВД России по г. Москве"}],"okato":null,"regionCode":"1145","region":"МОСКВА Г.","rayonCode":null,"rayon":null,"city":" ","street":"ВАРШАВСКОЕ Ш.","crossStreet":null,"house":"27","building":null,"corpus":null,"kilometer":null,"meter":null}],"wanted":[{"surname":"ЮЛДАШ УУЛУ","name":"УМАР","patronymic":null,"birthDate":"1975-05-25T00:00:00.000+0300","republic":"КИРГССР","region":"ДЖАЛАЛ-АБАДСКАЯ ОБЛ.","district":"СУЗАКСКИЙ Р-Н","city":"С.СУЗАК","passport":"5311 067299","wantedRepublic":"РФ","wantedRegion":"САМАРСКАЯ ОБЛ.","wantedDistrict":null,"wantedCity":"Г.САМАРА","article":"СТ.327 Ч.3","criminalCaseNumber":"10094","criminalCaseDate":"2014-01-22T00:00:00.000+0400","num_rd":"478","uvd":"ГУ МВД РОССИИ ПО САМАРСКОЙ ОБЛ.","rovd":"ОП №4 (ОКТЯБРЬСКИЙ)","restraint":"ПОДПИСКА О НЕВЫЕЗДЕ","num_cirk_sr":null,"mvd_ust":null,"date_ust":null,"snroz_name":null,"ustroz_name":null,"ustpomroz_name":null,"ustmestroz_name":null}],"wantedSP":[],"documents":[]}],"photo":null}');
        $this->fio->restraintLogger = $this->getMockBuilder(RestraintLogger::class)
            ->setMethods(['send'])
            ->getMock();
        parent::setUp();
    }

    /**
     * Проверка обработки входного запроса
     */
    public function testCreateRequest()
    {
        $array = array();
        $array['surname'] = 'котолюбов';
        $array['name'] = 'павел';
        $array['patronymic'] = 'михайлович';
        $array['year'] = '30.08.1988';
        $request = new \stdClass();
        $request->name = 'ПАВЕЛ';
        $request->surname = 'КОТОЛЮБОВ';
        $request->year = '1988';
        $request->patronymic = 'МИХАЙЛОВИЧ';
        $request->subdate = '3008';
        $this->assertEquals($request, $this->fio->createRequest($array));
    }


    /**
     * Проверка метода для логирования restraint
     */
    public function testdataRestraint()
    {
        $this->fio->dataRestraint();
        $this->assertEquals('ПОДПИСКА О НЕВЫЕЗДЕ', $this->fio->restraintLogger->restraint);
    }

    /**
     *
     */
    public function testGetResult()
    {
        $this->fio->getResult();
        $this->assertNotNull($this->fio->result);
    }

    /**
     * Проверка метода для опеределения данных о розыске
     */
    public function testGetWanted()
    {
        $this->fio->getWanted();
        $this->assertTrue($this->fio->searchLogger->wanted);
    }
}