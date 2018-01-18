<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.12.17
 * Time: 14:17
 */
require 'Registry.php';
require 'App.php';
require 'vendor/autoload.php';
require 'API/Chat.php';
spl_autoload_register("autoload");

function autoload($class) {
         // Controllers
        if (file_exists(__APPDIR__ . 'API/' . strtolower($class) . '.php')) {
            require_once __APPDIR__ . 'API/' . strtolower($class) . '.php';
            //ES_Controllers
        } elseif(file_exists(__APPDIR__ . '/' . strtolower($class) . '.php')) {
            require_once __APPDIR__ . '/' . strtolower($class) . '.php';
            // Models
        }
    App::$registry = new Registry();
    App::$registry['chat']=new Chat();
}
//include 'userfuncs.php';