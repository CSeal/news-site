<?php
/**
*Модуль отвечающий за цытат. Блок Quote
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class Quote extends Module{
 public function __construct(){
	parent::__construct();
	$this->add("quote");
 }
 
 /**
	*Метод возращает имя файла шаблона отвечающего за вывод модуля Quote(Умный цытаты)
	*
	*@return string
	*/
	public function getTmplFile(){
		return "quote";
	}
}
?>