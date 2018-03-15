<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ввода пароля.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidatePassword extends Validator
{
  /**
	*Максимальная длина пароля.
	*/
	const MAX_LEN = 50;
	
	/**
	*Минимальная длина пароля.
	*/
	const MIN_LEN = 6;
	
	/**
	*Код сообщения ошибки пустого пароля
	*/
	const CODE_EMPTY = "ERROR_PASSWORD_EMPTY";
	
	/**
	*Код сообщения ошибки неправильного содержимого пароля
	*/
	const CODE_CONTENT = "ERROR_PASSWORD_CONTENT";
	
	/**
	*Код сообщения ошибки слишком длинного пароля
	*/
	const CODE_MAX_LEN = "ERROR_PASSWORD_MAX_LEN";
	
	/**
	*Код сообщения ошибки слишком короткого пароля
	*/
	const CODE_MIN_LEN = "ERROR_PASSWORD_MIN_LEN";
	
	/**
	*Код сообщения ошибки не совпадения паролей
	*/
	const CODE_EQUIAL = "ERROR_PASSWORD_CONF";
	
	/**
	*Собщение об ошибки проверки пароля на коректность ввода данных.
	*/
	const CODE_INVALID = "ERROR_PASSWORD_CONTENT";
	
	/**
	*Проверяет корректность ввода пароля. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setErorrs(self::CODE_EMPTY);
		elseif(mb_strlen($data) < self::MIN_LEN) $this->setErorrs(self::CODE_MIN_LEN);
		elseif(mb_strlen($data) > self::MAX_LEN) $this->setErorrs(self::CODE_MAX_LEN);
		elseif(!preg_match("/^[a-z0-9_-!\?]$/i", $data))$this->setErorrs(self::CODE_CONTENT);
	}
}
?>