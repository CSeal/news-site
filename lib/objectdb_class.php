<?php
/**
*Абстрактный адаптер абстрактного класс Ядра AbstractObjectDB
*Расширяет возможности AbstractObjectDB. Добавлен метод вывода сокращённого названия месяца. Добавлены методы-обработчики событии "до и после редактирования поля"  
*
*@author Антон Манузин
*@package lib
*@version v1.0
*/
abstract class ObjectDB extends AbstractObjectDB
{
	/**
	*@var Array $months Массив содержащй список сокращённых названий месяцев
	*@access private
	*/
	private static $months = array("янв", "фев", "март", "апр", "май", "июнь", "июль", "авг", "сен", "окт", "ноя", "дек");
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы и формат даты
	*
	*@param string $table Имя таблицы
	*@access public
	*@return void
	*/
	public function __construct($table)
	{
		parent::__construct($table, Config::FORMAT_DATE);
	}

	/**
	*возращает сокращенное название текущего или месяца передоного в виде параметра $date
	*
	*@param string $date Переданная в метод дата 
	*@access protected
	*@return string
	*/	
	protected static function getMonth($date = null)
	{
		if($date) $date = strtotime($date);
		else $date = time();
		return self::$months[date("n", $date) - 1];
	}
	
	/*Событи перед изменеием значения поля
	*
	*@param string $field Имя поля  
	*@param mixed $value Значение
	*@access public
	*@return bool
	*/
	public function preEdit($field, $value)
	{
		return true;
	}
	
	/*Событи после изменеием значения поля
	*
	*@param string $field Имя поля  
	*@param mixed $value Значение
	*@access public
	*@return bool
	*/
	public function postEdit($field, $value)
	{
		return true;
	}
	
	protected function post_handling(){
		return true;
	}
}
?>