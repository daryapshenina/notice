<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 09.08.17
 * Time: 11:28
 */

namespace Search\Model;


class DataFromRequest
{
    /**
     * Получение название сервиса из названия маршрута
     * @param $url
     * @param $routeName
     * @return mixed
     */
    public static function getService($url, $routeName)
    {
        if ($routeName == 'newfis') {

            return $routeName;
        } else {
            $str = strpos($url, "?");
            $url = substr($url, 0, $str);
            $parts = explode('/', $url);
            return $parts[2];
        }
    }
//получение данных из запроса - ToDO будет дополняться
    public static function getRequest($request)
    {

        return $request;

    }

}