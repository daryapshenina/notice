<?php

namespace Authorization\Model;

use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use DOMDocument;
use Authorization\Module;

class Ais
{
    public $client;
    public $ais;
    public $tgt;
    public $params;

    public function __construct($username, $password, $ogai_code)
    {
        $configs = new Module;
        $this->params = $configs->getConfig()['ais'];
        $this->username = $username;
        $this->password = $password;
        $this->ogai_code = $ogai_code;
        $this->client = self::httpClient($this->params);
    }

    public static function httpClient($params)
    {
        $client = new Client();
        $clientConfig = array(
            'adapter' => new Curl(),
            'curloptions' => array(
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_SSL_VERIFYHOST => FALSE
            ),
        );
        $client->setUri($params['CAS_SERVER']);
        $client->setOptions($clientConfig);
        return $client;
    }

    public function login()
    {
        $this->tgt = $this->checkAuthorization();
        if (!$this->tgt) {
            $this->error = "Некорректный пароль или пользователь с указанным логином не зарегистрирован в СУДИС МВД России.";
            return FALSE;
        }

        if ($this->checkDepartment() === FALSE) {

            $this->error = "Неверно выбрано подразделение.";
            return FALSE;
        }


        if ($this->checkPermissions() === FALSE) {
            $this->error = "Данному пользователю запрещена авторизация.";
            return FALSE;
        }

        return TRUE;
    }

    public function send($uri, $postdata = null)
    {
        $this->client->reset();
        $this->client->setUri($this->params['CAS_SERVER'] . $uri);
        if (isset($postdata)) {
            if (is_array($postdata)) {
                $this->client->setParameterPost($postdata);
            } else $this->client->setRawBody($postdata);
            $this->client->setMethod('POST');
        }
        $response = $this->client->send()->getBody();
        return $response;

    }

    /**
     * Получить гранд тикет
     * @return mixed
     * @throws \Exception
     */
    public function checkAuthorization()
    {
        $post = array();
        $post['username'] = $this->username;
        $post['password'] = $this->preparePassword($this->password);
        $response = $this->send('/cas/v1/ticketsV2', $post);
        $response = json_decode((string)$response, true);
        switch ($response['code']) {
            case 'ok':
                $this->tgt = $response['tgt'];
                return ($this->tgt);
            case 'badCredentials':
                $out = "Некорректный пароль или пользователь с указанным логином не зарегистрирован в СУДИС МВД России.";
                throw new \Exception($out);
                break;
            case 'internalError':
                throw new \Exception("Сервис аутентификации СУДИС МВД России не доступен или вернул ошибку взаимодействия.");
                break;
            default:
                throw new \Exception($response["message"]);

        }
    }

    /**
     * Проверить, что с код департамента разрешен для авторизации
     * @return bool
     */
    public function checkDepartment()
    {
        $uri = '/cds/rest/tickets/departments/' . $this->tgt;
        $postdata = array('service' => $this->params['SERVICE_URL']);
        $st = (string)$this->send($uri, $postdata);
        $xml = $this->send('/cds/rest/departments/' . $st);
        $xml = json_decode($xml, true);
        foreach ($xml AS $node) {
            if ((int)$node["code"] === (int)$this->ogai_code) {
                return true;
            }
        }
        return false;
    }

    /**
     * Получение ответа от СУДИС
     * @return bool
     */
    public function checkPermissions()
    {
        $uri = '/cds/rest/tickets/permissions/' . $this->tgt;
        $postdata = array('department' => $this->ogai_code);
        $st = $this->send($uri, $postdata);
        $xml =
            '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
          <SOAP-ENV:Header/>
          <SOAP-ENV:Body>
            <samlp:Request xmlns:samlp="urn:oasis:names:tc:SAML:1.0:protocol" MajorVersion="1" MinorVersion="1" RequestID="" IssueInstant="">
              <samlp:AssertionArtifact>' . $st . '</samlp:AssertionArtifact>
            </samlp:Request>
          </SOAP-ENV:Body>
        </SOAP-ENV:Envelope>';
        $uri = '/cds/samlValidate?TARGET=' . $this->params['SERVICE_URL'];
        $response = $this->send($uri, $xml);
        if ($this->getUser($response) === FALSE) {
            return FALSE;
        }
        return true;
    }

    /**
     * Проверка на наличие доступа у пользователя и получение пользователя из ответа СУДИС
     * @param $xml
     * @return bool
     */
    public function getUser($xml)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->encoding = "utf-8";
        $dom->loadXML($xml);

        $status = $dom->getElementsByTagName("StatusCode")->item(0)->getAttribute("Value");
        if ($status === $this->params['REQUEST_DENIED'])
            return FALSE;
        foreach ($dom->getElementsByTagName("Attribute") AS $AttributeNode) {
            $attribute = (string)$AttributeNode->getAttribute("AttributeName");
            if ($attribute === "Roles") {
                $role = (string)$AttributeNode->getElementsByTagName("AttributeValue")->item(0)->nodeValue;
                if ($role !== $this->params['SUDIS_ACCESS']) {

                    return FALSE;
                }
            }
            $this->$attribute = (string)$AttributeNode->getElementsByTagName("AttributeValue")->item(0)->nodeValue;
        }
        return true;
    }

    /**
     * Подготовка пароля к отправке во внешний сервис
     * @param $password
     * @return string
     */
    public function preparePassword($password)
    {
        return urlencode(trim($password));
    }

    /**
     * Сменить пароль
     * @param $login
     * @param $password
     * @param $new_password
     */
    public function changePassword($login, $password, $new_password)
    {
        $uri = '/security-changepwd-web/rest/';
        $this->client->setHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'));

        $postBody = "login=" . $login . "&password=" . $this->preparePassword($password) . "&newpassword=" . $this->preparePassword($new_password);
        $this->send($uri, $postBody);
    }

}