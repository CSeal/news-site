<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ввода логина.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateLogin extends Validator
{
	/**
	*Максимальная длина логина.
	*/
	const MAX_LEN = 100;
	
	/**
	*Минимальная длина логина.
	*/
	const MIN_LEN = 6;
	
	/**
	*Код сообщения ошибки невведенного логина
	*/
	const CODE_EMPTY = "ERROR_LOGIN_EMPTY";
	
	/**
	*Код сообщения ошибки неккоректного логина
	*/
	const CODE_INVALID = "ERROR_LOGIN_INVALID";
	
	/**
	*Код сообщения ошибки слишком длинного логина
	*/
	const CODE_MAX_LEN = "ERROR_LOGIN_MAX_LEN";
	
	/**
	*Код сообщения ошибки слишком короткого логина
	*/
	const CODE_MIN_LEN = "ERROR_LOGIN_MIN_LEN";
	
	/**
	*Проверяет корректность ввода логина. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setErorrs(self::CODE_EMPTY);
		elseif(mb_strlen($data) < self::MIN_LEN) $this->setErorrs(self::CODE_MIN_LEN);
		elseif(mb_strlen($data) > self::MAX_LEN) $this->setErorrs(self::CODE_MAX_LEN);
		elseif($this->isContainQuotes($data))$this->setErorrs(self::CODE_INVALID);
	}
}
?>