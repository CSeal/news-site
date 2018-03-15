<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность заголовков.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateTitle extends Validator
{
	/**
	*Максимальная длина заголовка
	*/
	const MAX_LEN = 100;
	
	/**
	*Минимальная длина заголовка
	*/
	const MIN_LEN = 3;
	
	/**
	*Код сообщения ошибки пустого заголовка 
	*/
	const CODE_EMPTY = "ERROR_TITEL_EMPTY";
	
	/**
	*Код сообщения ошибки слишком длинного заголовка
	*/
	const CODE_MAX_LEN = "ERROR_TITEL_MAX_LEN";
	
	/**
	*Код сообщения ошибки слишком короткого заголовка
	*/
	const CODE_MIN_LEN = "ERROR_TITEL_MIN_LEN";
	
	/**
	*Проверяет корректность ввода заголовка. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setErorrs(self::CODE_EMPTY);
		elseif(mb_strlen($data) < self::MIN_LEN) $this->setErorrs(self::CODE_MIN_LEN);
		elseif(mb_strlen($data) > self::MAX_LEN) $this->setErorrs(self::CODE_MAX_LEN);
	}
}
?>