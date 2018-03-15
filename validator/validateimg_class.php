<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность имени загружаемой картинки на сервер.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateIMG extends Validator
{
	/**
	*Проверяет корректность имени загружаемой на сервер картинки. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	protected function validate()
	{
		$data = $this->data;
		if(!is_null($data) && !preg_match("/^[a-z0-9]+[a-z0-9-_]*\.(jpeg|jpg|bmp|png|gif)$/i", $data)) $this->setErorrs(self::CODE_UNKNOWN);
	}
}
?>