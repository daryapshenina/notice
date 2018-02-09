<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 11.12.17
 * Time: 19:10
 */
class PgConn
{
    public $dbconnect;

public function __construct()
{

    $host = "localhost";
    $user = "notice";
    $pass = "iddqd225";
    $db = "notice";

    $connect_string = "host=localhost port=5432 dbname=notice user=notice password=iddqd225";
    $this->dbconnect = pg_connect($connect_string);

}



}