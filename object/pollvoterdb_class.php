<?php
/**
*Класс, отвечающий за работу с обьктом PollVoter (Количество ответов)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
 class PollVoterDB extends ObjectDB
 {
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "poll_voters";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('poll_data_id', 'ValidateId');
		$this->add('ip', 'ValidateIP', self::TYPE_IP, $this->getIP());
		$this->add('date', 'ValidateDATE', self::TYPE_TIMESTAMP, $this->getDate());
	}
	
	/**
	*возвращает количество конкретных ответов на вопрос
	*
	*@param int $poll_data_id идентификатор ответа на опрос 
	*@return int
	*/
	public static function getCountOnPollDataID($poll_data_id)
	{
		return self::getCountOnField(self::$table, 'poll_data_id', $poll_data_id); 
	}
	
	/**
	*Проверяет отвечал ли поьзователь с таким IP на опрос
	*
	*@param array $poll_data_ids Массив идентификаторов опроса 
	*@return boolean
	*/
	public static function isAlreadyPoll($poll_data_ids)
	{
		$select = new select(self::$db);
		$select->from(self::$table, array('id'))
			   ->wherein('poll_data_id', $poll_data_ids)
			   ->where('`ip` = '.self::$db->getSQ(), array(ip2long($_SERVER['REMOTE_ADDR'])))
			   ->limit(1);
		return (self::$db->selectCell($select)) ? true : false;
	}
	
 }
?>