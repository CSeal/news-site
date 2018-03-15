<?php
/**
*Абстрактный Класс Ядра отсвечающий за проверку на корректность(Валидацию) данных при добавлении в БД.
*
*Экземпляры класа используются в  AbstractObjectDB классе и его наследниках. Реализован паттерн Стратегия
*AbstractObjectDB создается экземпляр класса, дочернеий от абстрактного клсса Validator. В конструктор передается данные и вызывается метод validate(), который должен быть перегружен для каждого дочернего класса  Validator
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class Validator
{
	
	/**
	*неизвестная ошибка. Системная констана. 
	*/
	const CODE_UNKNOWN = "UNKNOWN_ERROR";
	
	/**
	*@var array|string $data Данные для проверки
	*/
	protected $data;
	
	/**
	*@var array $errors Массив ошибок
	*/
	private $errors = array();
	
	/**
	*Конструктор класса. Данные для проверки записываются в переменную $data и вызывается метод для проверки данных validate();
	*@param array|string $data Данные для проверки
	*@return void
	*/	
	public function __construct($data)
	{
		$this->data = $data;
		$this->validate();
	}
	
	/**
	*Абстрактый метод проверки данных.Перегружется в дочерних классах
	*/	
	abstract protected function validate();
	/**
	*Возвращает массив ошибок;
	*@return array
	*/	
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	*Проверяет были ли ошибки.
	*@return bool
	*/	
	public function isValid()
	{
		return count($this->errors) == 0;
	}
	/**
	*Добавляет в массив errors код ошибки.
	@param string $code Код ошибки
	*@return void
	*/	
	public function setErrors($code)
	{
		$this->errors[] = $code;
	}
	/**
	*Проверяет строку на наличие ковычек.
	*@param string $string Строка для проверки
	*@return bool
	*/	
	protected function isContainQuotes($string)
	{
		$black_list = array("\"", "'", "`", "&quot;", "&apos;");
		foreach($black_list as $value)
		{
			if(strpos($string, $value) !== false) return true;
		}
		return false;
	}
}
?>