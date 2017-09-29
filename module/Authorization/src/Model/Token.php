<?php
/**
 * Created by PhpStorm.
 * User: altarix
 * Date: 23.06.17
 * Time: 16:28
 */

namespace Authorization\Model;

use Authorization\Interfaces\TokenStorageInterface;
use Authorization\Model\PGTokenStorage;

class Token
{
    /* @var $tokenStorage PGTokenStorage */
    private $tokenStorage;

    public function __construct($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Сгенерируем токен
     * @return string token
     * */
    public function generateToken()
    {
        return $this->tokenStorage->setToken(md5(time()));
    }

    /**
     * Проверить токен
     * @return array вернет массив с данными в таблице токен
     * */
    public function checkToken($token)
    {
        return $this->tokenStorage->checkToken($token);
    }

    /*
     * Удалить токен
     * */
    public function deleteToken($token)
    {
        return $this->tokenStorage->deleteToken($token);
    }
}