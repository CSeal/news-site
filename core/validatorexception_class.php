<?php
/**
*Класс Ядра отсвечающий за хранение и вывода ошибок валидатора.
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
class ValidatorException extends Exception
{
	/**
	*@var array $errors Массив ошибок
	*/
	private $errors;
	
	/**
	*Конструктор класса. Вызывается родительский конструктор. Массив ошибок $errors преданный в исключение записывается в свойство класа $errors.
	*param array $errors Массив ошибок
	*@return void
	*/	
	public function __construct($errors)
	{
		parent::__construct();
		$this->errors = $errors;
	}
	/**
	*Выводится массив ошибок errors
	*@return array
	*/	
	public function getErrors()
	{
		return $this->errors;
	}
} 
?>