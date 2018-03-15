<?php
/**
*Класс, отвечающий за работу с обьктом Quote (Коментарии)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/

class QuoteDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "quotes";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('author', 'ValidateTitle');
		$this->add('text', 'ValidateSmallText');
	}
	
	/**
	*Загружает в обьект одну случайную цетату
	*@return boolean
	*/
	public function loadRandom()
	{
		$select = new Select(self::$db);
		$select->from(self::$table, '*')
			   ->orderRand()
			   ->limit(1);
		$row = self::$db->selectRow($select);
		return $this->init($row);
	}
}
?>