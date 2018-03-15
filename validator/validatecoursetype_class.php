<?php
/**Класс-Валидатор отсвечающий за проверку типа курса.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateCourseType extends Validator
{
	/**
	*Максимальное значение типа курса. Важность курсов по убыванию(1 - самый важный(Платный курс), 3 - бесплатный вэбинар).
	*/
	const MAX_COURSE_TYPE = 3;

	/**
	*Класс-Валидатор отсвечающий за проверку типа курса. Вслучае если проверка не пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/	
	protected function validate()
	{
		$data = $this->data;
		if(is_null($data)) return;
		if(!is_int($data)) $this->setErrors(self::CODE_UNKNOWN);
		else
		{
			if(($data < 1) || ($data > self::MAX_COURSE_TYPE)) $this->setErrors(self::CODE_UNKNOWN);
		}
	}
}
?>