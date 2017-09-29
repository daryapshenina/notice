<?php
namespace Authorization\Interfaces;

interface TokenStorageInterface
{
    public function setToken($token);

    public function checkToken($token);

    public function deleteToken($token);
}