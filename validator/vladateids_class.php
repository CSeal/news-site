<?php
/**Класс-Валидатор отсвечающий за проверку списка идентификаторов.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateIDs extends Validator
{
	/**
	*Класс-Валидатор отсвечающий за проверку списка идентификаторов. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(is_null($data)) $this->setErrors(self::CODE_UNKNOWN);
		if(preg_match("/^\d+(,\d+)*\d?$/", $data)) $this->setErrors(self::CODE_UNKNOWN);
	}
}
?>