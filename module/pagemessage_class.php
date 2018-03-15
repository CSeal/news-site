<?php
/**
*Клас Отвечающий за вывод(отрисовку) системных сообщений
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class PageMessage extends Module{
	public function __construct(){
		parent::__construct();
		$this->add("header");
		$this->add("text");
	}

	
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля PageMessage(вывод системных сообщений)
*
*@return string
*/	
	public function getTmplFile(){
		return "pagemessage";
	}
}
?>