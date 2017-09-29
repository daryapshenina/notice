<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.08.17
 * Time: 17:32
 */

namespace Search\Model;


class UserInfo
{
    public $ogaiCode;
    public $login;
    public $password;


//ToDo дописать получение данных инспектора

    public function setInfo($token)
    {
       // $inspector = $this->send('authorization' );
        $this->ogaiCode = '650';
        $this->login = 'testqa6';
        $this->password = 'Abc_123';
        $this->imei = '11.0.0.1';
        $this->token=$token;
        $this->ip='11.0.0.1';
    }

    public function getOgaiCode()
    {
        return $this->ogaiCode;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }
}