<?php
/**
*Класс, отвечающий за работу с обьктом Poll (Опрос)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/

class PollDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "polls";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('title', 'ValidateTitle');
		$this->add('state', 'ValidateBoolean', null, 0);
	}
	
	/**
	*Загружает в обьект один случайный вопрос голосования который включен(state = 1). 
	*@return boolean
	*/
	public function loadRandom()
	{
		$select = new Select(self::$db);
		$select->from(self::$table, '*')
			   ->where('`state` = '.self::$db->getSQ(), array('1'))
			   ->orderRand()
			   ->limit(1);
		$row = self::$db->selectRow($select);
		return $this->init($row);
	}
}
?>