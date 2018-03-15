<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность URI(/index.php?param=value).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateURI extends Validator
 {
	/**
	*Максимальная длина URI
	*/
	const MAX_LEN = 255;
	
	/**Проверяет корректность URI. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) > self::MAX_LEN) $this->setErrors(self::CODE_UNKNOWN);
		else
		{
			$pattern = "~^(?:/[a-z0-9.,_@%&?=+\~/-]*)?(?:#[^ '\"&<>]*)?$~i";
			if(!preg_match($pattern, $data)) this->setErrors(self::CODE_UNKNOWN);
		}
	}
 }
?>