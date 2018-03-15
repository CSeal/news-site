<?php
/**Класс-Валидатор отсвечающий за проверку коректности превода даты вида dd.mm.YY H:M:S в строку(число секундс 01.01.1970).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateDate extends Validator
 {
	/**
	*Проверяет коректности превода даты вида dd.mm.YY H:M:S в строку(число секундс 01.01.1970). Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	protected function validate()
	 {
		 $data = $this->data;
		 if(!is_null($data) && strtotime($data) === false) $this->setErrors(self::CODE_UNKNOWN);
	 }
 }
?>