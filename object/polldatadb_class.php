<?php

/**
*Класс, отвечающий за работу с обьктом PollData (Ответ на опрос)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
 class PollDataDB extends ObjectDB
 {
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "poll_data";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('poll_id', 'ValidateId');
		$this->add('title', 'ValidateTitle');
	}
	
	/**
	*Возвращает масив обьектов всех записей ответов на опрос по определённому идентификатору конкретного опроса 
	*
	*@param int $poll_id идентификатор опроса 
	*@return array
	*/
	public static function getAllOnPollID($poll_id)
	{
		return self::getAllOnFieald(self::$table, __CLASS__, 'poll_id', $poll_id, 'id'); 
	}
	
	/**
	*Возвращает масив обьектов всех записей ответов на опрос по определённому идентификатору конкретного опроса включая новое свойство  voters = количество голосов у конкретного ответа.
	*Масив отсартирован пользовательской сортировкой в порядке убывания
	*
	*@param int $poll_id идентификатор опроса 
	*@return array
	*/
	public static function getAllDataOnPollID($poll_id)
	{
		$poll_data = self::getAllOnPollID($poll_id);
		$ch = 0;
		foreach($poll_data as $pd)
		{
			$pd->voters = PollVoterDB::getCountOnPollDataID($pd->id);
		}
		uasort($poll_data, array(__CLASS__, "compare"));
		return $poll_data;
	}
	
	/**
	*Сортировка масива обьктов ответов на конкретный опрос в порядке убывания
	*
	*@param object $value_1  первый элемент массива для сравнения
	*@param object $value_2  второй элемент массива для сравнения
	*@return int
	*/
	private static function compare($value_1, $value_2)
	{
		return ($value_1->voters < $value_2->voters) ? 1 : -1;// Оригинал return $value_1->voters < $value_2->voters 
	}
 }
?>