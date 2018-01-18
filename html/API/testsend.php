<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.12.17
 * Time: 11:54
 */

require '../vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Test{


    protected static $_instance;
    public  static $clients;

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function __clone() {
    }
    public static function setClients($clients){
        self::$clients=$clients;
    }
    public static function getClients(){
        return self::$clients;

    }
}
