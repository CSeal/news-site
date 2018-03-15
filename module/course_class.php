<?php
/**
*Класс создающий обьект отвечающий за отображение модуля вывода бесплатных курсов, семинаров и рассылок
*
*@author Антон Манузин
*@package module
*@version v1.0
*/

class Course extends Module{
	public function __construct(){
		parent::__construct();
		$this->add("auth_user");
		$this->add("courses");
	}
	
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля Course(Блок бесплатных курсов, семинаров и рассылок)
*
*@return string
*/
	public function getTmplFile(){
		return "course";
	}
}
?>