<?php
/**
*Класс отвечающий за отрисовку модуля верхнего меню.
*В свойстве uri хранится uri-адрес активного меню. 
*В свойстве items все остальные свойства: адресса сылок меню и т.д.
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class TopMenu extends Module{
 public function __construct(){
	 parent::__construct();
	 $this->add("uri");
	 $this->add("items");
 }
 
 /**Метод двозращает имя файла шаблона отвечающего за вывод модуля TopMenu(Верхнего меню навигации)
*
*@return string
*/
 protected function getTmplFile(){
	 return "topmenu";
 }
}

?>