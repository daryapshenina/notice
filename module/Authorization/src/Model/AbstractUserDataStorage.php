<?php

namespace Authorization\Model;

/**
 * Абстракция над хранилищем данных о залогиненых пользователях.
 * 
 * @author SotnikovDS
 *
 */
abstract class AbstractUserDataStorage
{
	/**
	 * Реализация хранилища в Postgre SQL
	 * 
	 * @var integer
	 */
	const POSTGRE_SQL = 1;
	
	/**
	 * Сохраняет значение по ключу
	 * 
	 * @param string $key
	 * @param string $value
	 */
	public abstract function set(string $key, string $value) : void;
	
	/**
	 * Возвращает значение по ключу
	 * 
	 * @param 	string $key Ключ
	 * @return 	string 		Значение
	 */
	public abstract function get(string $key);
	
	/**
	 * Возвращает конкретную реализацию по типу
	 * 
	 * @param self::CONST $type Тип реализованного хранилища
	 */
	public static function create($type = null) : self
	{
		$ret = null;
		
		switch ($type)
		{
			case self::POSTGRE_SQL:
				$ret = new PostgreUserDataStorage();
				break;
			default:
				$ret = new PostgreUserDataStorage();
				break;
		}
		
		return $ret;
	}
	
}