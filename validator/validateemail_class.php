<?php
/**Класс-Валидатор отсвечающий за проверку коректности ввода EMAIL-адреса.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateEmail extends Validator
{

	/**
	*Максимальная длина EMAIL-адреса.
	*/	
	const MAX_LEN = 100;
	
	/**
	*Собщение об ошибки проверки EMAIL-адреса на пустоту ввода данных.
	*/
	const CODE_EMPTY = "ERROR_EMAIL_EMPTY";
	
	/**
	*Собщение об ошибки проверки EMAIL-адреса на не коректность ввода данных.
	*/
	const CODE_INVALID = "ERROR_EMAIL_INVALID";
	
	/**
	*Собщение об ошибки проверки EMAIL-адреса на превышение допустимой длины.
	*/
	const CODE_MAX_LEN = "ERROR_EMAIL_MAX_LEN";
	
	/**
	*Проверяет коректности ввода EMAIL-адреса.
	*@return void
	*/	
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setErrors(self::CODE_EMPTY);
		elseif(mb_strlen($data) > self::MAX_LEN) $this->setErrors(self::CODE_MAX_LEN);
		else
		{
			$pattern = "/^[a-z0-9_][a-z0-9\.-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]$/i";
			if(!preg_match($pattern, $data)) $this->setErrors(self::CODE_INVALID);
			
		}
	}
} 
?>