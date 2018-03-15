<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность IP адресов.
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/	
class ValidateIP extends Validator
{
	/**
	*Проверяет корректность IP адресов. Вслучае если проверка не  пройдена, то добавляется в массив errors код ошибки CODE_UNKNOWN.
	*@return void
	*/
 protected function validate()
 {
	$data = $this->data;
	if($data == 0) return;
	if(!preg_match("/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/", $data)) $this->setErorrs(self::CODE_UNKNOWN);
	else
	{
		$temp_arr = explode(".", $data);
		for($i = 0; $i < 4; $i++)
		{
			if(($temp_arr[$i] < 0) || ($temp_arr[$i] > 254)) $this->setErorrs(self::CODE_UNKNOWN);
		}
	}
 }
}
?>