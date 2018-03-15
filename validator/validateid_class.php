<?php
/**
*Класс-Валидатор отсвечающий за проверку Id(могут находится в скрытых полях).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateId extends Validator
{
	/**
	*Проверяет кId(могут находится в скрытых полях). Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	protected function validate()
	{
		$data = (int)$this->data;
		if(!is_null($data) && (!is_int($data) || ($data) < 0)) $this->setErrors(self::CODE_UNKNOWN);
	}
}
?>