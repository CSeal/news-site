<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ввода текста статьи(Большого текста).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateText extends Validator
{
  /**
	*Максимальная длина текста стати.
	*/
	const MAX_LEN = 50000;
	
	/**
	*Код сообщения ошибки пустой статьи 
	*/
	const CODE_EMPTY = "ERROR_TEXT_EMPTY";
	
		
	/**
	*Код сообщения ошибки слишком длинной статьи
	*/
	const CODE_MAX_LEN = "ERROR_TEXT_MAX_LEN";
	
	/**
	*Проверяет корректность ввода текста статьи. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки соответсвующей одной из предопределёных констант.
	*@return void
	*/
	protected function validate()
	{
		$data = $this->data;
		if(mb_strlen($data) == 0) $this->setErorrs(self::CODE_EMPTY);
		elseif(mb_strlen($data) < self::MIN_LEN) $this->setErorrs(self::CODE_MIN_LEN);
		elseif(mb_strlen($data) > self::MAX_LEN) $this->setErorrs(self::CODE_MAX_LEN);
	}
}
?>
