<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17.01.18
 * Time: 14:12
 */
require '../API/testsend.php';
Test::getInstance();
$client=Test::getClients();
var_dump(count($client));
die;