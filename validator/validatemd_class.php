<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность описание в мета-тегах(SEO).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateMD extends Validator
 {
	/**
	*Максимальная длина описание.
	*/
	 const MAX_LEN = 255;
	 
	/**
	*Код сообщения ошибки пустого описания 
	*/
	 const CODE_EMPTY = "ERROR_MD_EMPTY";
	 
	/**
	*Код сообщения ошибки привышения длины описания
	*/
	 const CODE_MAX_LEN = "ERROR_MD_MAX_LEN";
	 
	/**
	*Проверяет корректность описание в мета-тегах. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
	*@return void
	*/
	 protected function validate()
	 {
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		if(mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
	 }
 }
?>