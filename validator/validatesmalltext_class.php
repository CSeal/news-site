<?php
/**
*Класс-Валидатор отсвечающий за проверку на корректность ввода текста коментария(маленького текста).
*
*
*@author Антон Манузин
*@package validator
*@version v1.0
*/
class ValidateSmallText extends ValidateText
{
	/**
	*Максимальная длина текста коментария.
	*/
	const MAX_LEN = 500;
}
?>