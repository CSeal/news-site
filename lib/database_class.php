<?php
/**
*Адаптер Абстрактного класс Ядра AbstractDataBase(отвечает за создания подключения к базе данных MySQL)
*Реализован Паттер "Одиночка"
*
*@author Антон Манузин
*@package lib
*@version v1.0
*/
class DataBase extends AbstractDataBase{
	/**
	*@var Object $db Содержит обьект подключения к БД
	*/
	private static $db;
	

	/**
	*Возвращает обьект подключения к БД. Реализация Паттерна "Одиночка"
	*@access public
	*@return Object
	*/
	public static function getDBO()
	{
		if(self::$db === null) self::$db = new DataBase(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME, Config::DB_SYM_QUERY, Config::DB_PREFIX);
		return self::$db;
	}

}
?>