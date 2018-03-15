<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ключевых слов в мета-тегах(SEO).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
 class ValidateMK extends Validator
 {
	/**
	*Максимальная длина ключевых слов.
	*/
	 const MAX_LEN = 255;
	 
	/**
	*Код сообщения ошибки пустого ключевого слова 
	*/
	 const CODE_EMPTY = "ERROR_MK_EMPTY";
	 
	/**
	*Код сообщения ошибки привышения длины ключевого слова
	*/
	 const CODE_MAX_LEN = "ERROR_MK_MAX_LEN";
	 
	/**
	*Проверяет корректность ключевых слов в мета-тегах. В случае если проверка не пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
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