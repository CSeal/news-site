<?php
/**
*Класс, отвечающий за работу с обьктом Menu (Все типы меню)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
class MenuDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "menu";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('type', 'ValidateId');
		$this->add('title', 'ValidateTitle');
		$this->add('link', 'ValidateURL');
		$this->add('parent_id', 'ValidateId');
		$this->add('external', 'ValidateBoolean');
	}
	
	/**
	*Возращает все поля тоблицы Menu где тип соответствуют константе TOPMENU. Строки собираются в обьекты и упаковываются в масив
	*Константа TOPMENU задается в файле start.php
	*@return array
	*/
	public static function getTopMenu()
	{
		return AbstractObjectDB::getAllOnFieald(self::$table, __CLASS__, "type", TOPMENU, "id");//getAllOnFieald
	}
	
	/**
	*Возращает все поля тоблицы Menu где тип соответствуют константе MAINMENU. Строки собираются в обьекты и упаковываются в масив
	*Константа MAINMENU задается в файле start.php
	*@return array
	*/
	public static function getMainMenu()
	{
		return AbstractObjectDB::getAllOnFieald(self::$table, __CLASS__, "type", MAINMENU, "id");
	}
}
?>