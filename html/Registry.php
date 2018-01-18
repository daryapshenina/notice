<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 22.12.17
 * Time: 14:19
 */
Class Registry Implements ArrayAccess {

    private $vars;

    /**
     * Метод установки пары ключ-значение
     * @param string $key ключ
     * @param mixed $var значение
     * @return boolean
     * @throws Exception такой ключ уже существует
     */
    function set($key, $var) {
        if (isset($this->vars[$key]) == true) {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }
        $this->vars[$key] = $var;
        return true;
    }

    /**
     * метод получения пары ключ-значение
     * @param string $key ключ
     * @return null
     */
    function get($key) {
        if (isset($this->vars[$key]) == false) {
            return null;
        }
        return $this->vars[$key];
    }

    /**
     * метод удаления пары ключ-значение
     * @param string $key ключ
     */
    function remove($key) {
        unset($this->vars[$key]);
    }

    /* следующие методы реализуют методы интерфейса ArrayAccess
      для получения доступа к атрибутам класса как к элементам массива */

    /**
     * Проверяет на существование ключ
     * @param string $offset ключ
     * @return boolean
     */
    function offsetExists($offset) {
        return isset($this->vars[$offset]);
    }

    /**
     * Полчает значение по ключу
     * @param string $offset ключ
     * @return mixed
     */
    function offsetGet($offset) {
        return $this->get($offset);
    }

    /**
     * Устанавливает значение ключу
     * @param string $offset ключ
     * @param mixed $value значение
     */
    function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    /**
     * Удаляет пару ключ-значение
     * @param string $offset ключ
     */
    function offsetUnset($offset) {
        unset($this->vars[$offset]);
    }

    /**
     * Метод генерации UUID
     * @param type $postfix
     * @return type
     */
    function uuid($postfix = '') {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);

        return $uuid . $postfix;
    }

}
