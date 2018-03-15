<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ввода логина.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateName extends Validator
{
	/**
	*Максимальная длина имени.
	*/
	const MAX_LEN = 100;
	
	/**
	*Минимальная длина имени.
	*/
	const MIN_LEN = 2;
	
	/**
	*Код сообщения ошибки невведенного имени
	*/
	const CODE_EMPTY = "ERROR_NAME_EMPTY";
	
	/**
	*Код сообщения ошибки неккоректного имени
	*/
	const CODE_INVALID = "ERROR_NAME_INVALID";
	
	/**
	*Код сообщения ошибки слишком длинного имени
	*/
	const CODE_MAX_LEN = "ERROR_NAME_MAX_LEN";
	
	/**
	*Код сообщения ошибки слишком короткого имени
	*/
	const CODE_MIN_LEN = "ERROR_NAME_MIN_LEN";
	
	/**
	*Проверяет корректность ввода имени. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
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