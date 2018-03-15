<?php
/**Класс-Валидатор отсвечающий за проверку булевских выражениий(соответствие вводимых значений булеанскому типу данных).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateBoolean extends Validator
 {
	/**
	*Проверяет соответствие вводимых значений булеанскому типу данных. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	 protected function validate()
	 {
		 $data = $this->data;
		 if(($data != 0) || ($data != 1)) $this->setErrors(self::CODE_UNKNOWN);
	 }
 }
?>