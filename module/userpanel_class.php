<?php
/**
*Класс отвечающий за создание модуля выдачи панели пользователя(UserPanel)
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class UserPanel extends Module{
	public function __construct(){
		parent::__construct();
		$this->add("user");
		$this->add("uri");
		$this->add("items");
	}
/**
*Метод добовляет в свойство items модуля UserPanel обект содержащий свойства: адресс ссылки и заголовок разделов этой панели 
*
*@return void
*/	
	public function addItem($link, $title){
		$cl = new stdclass();
		$cl->link = $link;
		$cl->title = $title;
		$this->items = $cl;
	}
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля UserPanel(Панель пользователя)
*
*@return string
*/
	public function getTmplFile(){
		return "userpanel";
	}
}
?>