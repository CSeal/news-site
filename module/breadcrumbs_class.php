<?php
/**
*Клас отвечающий за создание модуля BreadCrumbs(Хлебные крошки или верхнее меню навигации)
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class BreadCrumbs extends Module{
	public function __construct(){
		parent::__construct();
		$this->add("data", null, true);
	}
	
/**
*Метод добавления значений свойству data масива properties
*
*@param string $title Заголовок раздела 
*@param string|boolean $link Адрес ссылки раздела. Может быть лож чтоб обозначить что это не ссылка(текущее меню раздела)
*@return void
*/	
	public function addDataItem($title, $link = false){
		$cl = new stdClass();
		$cl->title = $title;
		$cl->link = $link;
		$this->data = $cl;
	}
	
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля HeadBreadCrumbs(Хлебные крошки или верхнее меню навигации)
*
*@return string
*/	
	public function getTmplFile(){
		return "breadcrumbs";
	}
}
?>