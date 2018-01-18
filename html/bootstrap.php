<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.12.17
 * Time: 14:16
 */

session_start();
if (!defined('__DIR__'))
    define('__DIR__', dirname(__FILE__));


/**
 * абсолютный путь к корневому каталогу приложения
 */
define('__APPDIR__', dirname(__FILE__).'/');
define('__ROOT__', strtr($_SERVER['SCRIPT_NAME'], array('index.php' => '')));

//$server_paths = array('appDir' => __APPDIR__, 'rootUri' => strtr($_SERVER['SCRIPT_NAME'], array('index.php' => '')));

define("__SHCEMA__", "GIBDD_APR"); // режим работы с внешним сервисом ГИБДД
define("__MODE__", "TEST");

include_once 'init.php';