<?php
 /**
*Класс-Валидатор отсвечающий за проверку на корректность URL(http://mysite.net/index.php?param=value; /index.php?param=value;).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateURL extends Validator
 {
	/**
	*Максимальная длина URI
	*/
	const MAX_LEN = 255;
	
	/**Проверяет корректность URL. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) > self::MAX_LEN) $this->setErrors(self::CODE_UNKNOWN);
		else
		{
			$pattern1 = "~^(?:(?:https&|ftp|telnet))://(?:[a-z0-9_-]{1,32}".
			"?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128})\.)+(?:com|net|".
			"org|mil|edu|arpa|gov|biz|info|aero|inc|name|local|[a-z]{2}|(?!0)(?:(?".
			"!0[^.]|255)[0-9]{1,3}\.){3}(,!0/255)[0-9]{1,3})(?:/[a-z0-9.,_@%&".
			"?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i";
			$pattern2 = "~^(?:/[a-z0-9.,_@%&?=+\~/-]*)?(?:#[^ '\"&<>]*)?$~i";
			if(!preg_match($pattern1, $data) && !preg_match($pattern2, $data)) this->setErrors(self::CODE_UNKNOWN);
		}
	}
 }
?>