<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность хеша активации пользователя.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateActivation extends Validator
{
	/**
	*Максимальная длина хеша.
	*/
	const MAX_LEN = 100;
	
	/**
	*Проверяет количество символов в хеше. Вслучае если количество  привышает  MAX_LEN, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) > self::MAX_LEN) $this->setErrors(self::CODE_UNKNOWN);
	}
}
?>