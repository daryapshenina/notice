<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14.08.17
 * Time: 13:08
 */

namespace Search\Model;

use PHPUnit\Framework\TestCase;
use Search\Model\Fis;

class FisTest extends TestCase
{
    public function setUp()
    {
        $this->responseLogger = new ResponseLogger();
        $this->searchLogger = new SearchLogger();
        $this->serviceLogger = new ServiceLogger();
        $this->restraintLogger = new RestraintLogger();
        $this->fis = new Fis($this->responseLogger, $this->searchLogger, $this->serviceLogger, $this->restraintLogger);
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
        $array['patronymic'] = 'семёнович';
        $array['year'] = '30.08.1988';
        $request = new \stdClass();
        $request->name = 'ПАВЕЛ';
        $request->surname = 'КОТОЛЮБОВ';
        $request->year = '1988';
        $request->patronymic = 'СЕМЕНОВИЧ';
        $request->subdate = '3008';
        $this->assertEquals($request, $this->fis->createRequest($array));
    }

    public function testGetWanted()
    {
        $this->fis->response = json_decode('{"@type":"fisDriverSearchResponse","errors":[],"responseCode":"OK","drivers":[{"birthDate":"3112","birthPlace":"СОБИНКА","birthYear":"1966","city":"СОБИНКА","house":"176","id":"2103099265#1","name":"ГАЛИНА","nation":4404,"patronymic":"ВЛАДИМИРОВНА","region":"КРАСНОДАРСКИЙ КРАЙ","street":"РИМСКОГО-КОРСАКОВА","surname":"БЕЛОУСОВА","violations":[{"marka":"ВОЛЬВО","measures":[{"decisionDate":"2011-11-22T00:00:00","penaltyAmount":500,"postNumber":"23ДЯ101566","violationId":"2103099265#1"}],"model":"ХС60","regno":"К777ВР197","stotvCode":121511,"violationDate":"2011-11-22T00:00:00","violationId":"2103099265#1"}]}]}');
        $this->fis->getWanted();
        $this->assertFalse($this->fis->searchLogger->wanted);

    }

    /**
     * Проверка метода для удаления лишних водителей
     */
    public function testFindDrivers()
    {
        $this->fis->response = json_decode('{"responseCode": "OK","responseComment": null,"errors": [], "drivers": [{"surname": "ДРОЗДОВ", "name": "ЕГОР","patronymic": "ЕВГЕНЬЕВИЧ","birthYear": "1985","birthDate": "2803","region": "МОСКОВСКАЯ ОБЛАСТЬ","city": "КОРОЛЕВ","street": null,"house": null,"corpus":null,"flat": null,"nation": "4404","gender": null,"birthPlaceCode": null,"birthPlace": "МОСКОВСКАЯ ОБЛ.", "phone": null,"violations": [{"violationDate": "2014-05-06T00:00:00.000+0400", "violationInputDate": "2014-07-17T00:00:00.000+0400", "ogaiName": "1145611","ogaiCode": null, "stotvCode": 121510,"pddCode": null,"regno": "Х222УН190","marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ", "measures": [{ "postNumber": "77МО6979992", "decisionDate": "2014-05-17T00:00:00.000+0400", "decision": null, "penaltyAmount": 1500, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null,"arestEndDate": null,"decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null }], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null,"city": null,"street": null,"crossStreet": null,"house": null, "building": null,"corpus": null, "kilometer": null,"meter": null } ] }, { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ","birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": null, "street": null, "house": null, "corpus": null, "flat": null, "nation": null, "gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛАСТЬ ЩЕЛКОВО Г.", "phone": null, "violations": [ { "violationDate": "2014-06-26T00:00:00.000+0400", "violationInputDate": "2014-07-18T00:00:00.000+0400",
 "ogaiName": "1146511", "ogaiCode": null, "stotvCode": 12093, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ ОVЕRLАND", "measures": [ { "postNumber": "18810150140", "decisionDate": "2014-07-15T00:00:00.000+0400", "decision": null, "penaltyAmount": 1000, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null, "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null } ] }, { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ", "birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": "КОРОЛЕВ", "street": null, "house": null, "corpus": null, "flat": null, "nation": "4404","gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛАСТЬ ЩЕЛКОВО Г.", "phone": null, "violations": [ { "violationDate": "2014-09-03T00:00:00.000+0400", "violationInputDate": "2014-12-27T00:00:00.000+0300", "ogaiName": "1145519", "ogaiCode": null, "stotvCode": 12092, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ ОVЕRLАND", "measures": [ { "postNumber": "18810177140", "decisionDate": "2014-09-12T00:00:00.000+0400", "decision": null, "penaltyAmount": 500, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null, "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null } ] }, { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ", "birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": "КОРОЛЕВ", "street": null, "house": null, "corpus": null, "flat": null, "nation": "4404", "gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛАСТЬ ЩЕЛКОВО Г.", "phone": null, "violations": [ { "violationDate": "2014-09-03T00:00:00.000+0400", "violationInputDate": "2014-12-27T00:00:00.000+0300", "ogaiName": "1145519", "ogaiCode": null, "stotvCode": 12092, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ ОVЕRLАND", "measures": [ { "postNumber": "18810177140", "decisionDate": "2014-09-12T00:00:00.000+0400", "decision": null, "penaltyAmount": 500, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null, "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null} ] } ]}');
        $this->fis->findDrivers();
        $result = '{ "responseCode": "OK", "responseComment": null, "errors": [],
 "drivers": [  { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ", "birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": "КОРОЛЕВ", "street": null, "house": null, "corpus": null, "flat": null, "nation": "4404", "gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛ.", "phone": null, "violations": [  {
 "violationDate": "2014-05-06T00:00:00.000+0400", "violationInputDate": "2014-07-17T00:00:00.000+0400", "ogaiName": "1145611", "ogaiCode": null, "stotvCode": 121510, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ", "measures": [  { "postNumber": "77МО6979992", "decisionDate": "2014-05-17T00:00:00.000+0400", "decision": null, "penaltyAmount": 1500, "deprivationDays": null, "deprivationMonths": null,
 "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null  } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null,
 "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null  } ]  },  { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ", "birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": null, "street": null, "house": null, "corpus": null, "flat": null, "nation": null, "gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛАСТЬ ЩЕЛКОВО Г.", "phone": null, "violations": [  { "violationDate": "2014-06-26T00:00:00.000+0400", "violationInputDate": "2014-07-18T00:00:00.000+0400", "ogaiName": "1146511", "ogaiCode": null, "stotvCode": 12093, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ ОVЕRLАND", "measures": [  { "postNumber": "18810150140", "decisionDate": "2014-07-15T00:00:00.000+0400", "decision": null, "penaltyAmount": 1000, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null  } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null, "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null  } ]  },  { "surname": "ДРОЗДОВ", "name": "ЕГОР", "patronymic": "ЕВГЕНЬЕВИЧ", "birthYear": "1985", "birthDate": "2803", "region": "МОСКОВСКАЯ ОБЛАСТЬ", "city": "КОРОЛЕВ", "street": null, "house": null, "corpus": null, "flat": null, "nation": "4404", "gender": null, "birthPlaceCode": null, "birthPlace": "МОСКОВСКАЯ ОБЛАСТЬ ЩЕЛКОВО Г.", "phone": null, "violations": [  { "violationDate": "2014-09-03T00:00:00.000+0400", "violationInputDate": "2014-12-27T00:00:00.000+0300", "ogaiName": "1145519", "ogaiCode": null, "stotvCode": 12092, "pddCode": null, "regno": "Х222УН190", "marka": "ДЖИП", "model": "ГРАНД ЧЕРОКИ ОVЕRLАND", "measures": [  { "postNumber": "18810177140", "decisionDate": "2014-09-12T00:00:00.000+0400", "decision": null, "penaltyAmount": 500, "deprivationDays": null, "deprivationMonths": null, "deprivationBeginDate": null, "divestmentEndDate": null, "arestInDays": null, "arestBeginDate": null, "arestEndDate": null, "decisionExecuteDate": null, "decisionEntryDate": null, "priz2025": null  } ], "okato": null, "regionCode": null, "region": null, "rayonCode": null, "rayon": null, "city": null, "street": null, "crossStreet": null, "house": null, "building": null, "corpus": null, "kilometer": null, "meter": null  } ]  } ]}';
        $result = json_decode($result);
        sort($result->drivers);
        $this->assertEquals($result->drivers, $this->fis->response->drivers);
    }

    /**
     * Проверка метода обработки ответа сервиса
     */
    public function testGetResult()
    {
        $this->fis->response = json_decode('{"@type":"fisDriverSearchResponse","errors":[],"responseCode":"OK","drivers":[{"birthDate":"3112","birthPlace":"СОБИНКА","birthYear":"1966","city":"СОБИНКА","house":"176","id":"2103099265#1","name":"ГАЛИНА","nation":4404,"patronymic":"ВЛАДИМИРОВНА","region":"КРАСНОДАРСКИЙ КРАЙ","street":"РИМСКОГО-КОРСАКОВА","surname":"БЕЛОУСОВА","violations":[{"marka":"ВОЛЬВО","measures":[{"decisionDate":"2011-11-22T00:00:00","penaltyAmount":500,"postNumber":"23ДЯ101566","violationId":"2103099265#1"}],"model":"ХС60","regno":"К777ВР197","stotvCode":121511,"violationDate":"2011-11-22T00:00:00","violationId":"2103099265#1"}]}]}');
        $this->fis->getResult();
        $result = json_decode('[{"birthDate":"3112","birthPlace":"СОБИНКА","birthYear":"1966","city":"СОБИНКА","house":"176","id":"2103099265#1","name":"ГАЛИНА","nation":4404,"patronymic":"ВЛАДИМИРОВНА","region":"КРАСНОДАРСКИЙ КРАЙ","street":"РИМСКОГО-КОРСАКОВА","surname":"БЕЛОУСОВА","violations":[{"marka":"ВОЛЬВО","measures":[{"decisionDate":"2011-11-22T00:00:00","penaltyAmount":500,"postNumber":"23ДЯ101566","violationId":"2103099265#1","decision":""}],"model":"ХС60","regno":"К777ВР197","stotvCode":121511,"violationDate":"2011-11-22T00:00:00","violationId":"2103099265#1"}]}]');
        $this->assertEquals($result, $this->fis->result);
    }
}