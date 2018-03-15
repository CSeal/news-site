<?php
/**
*Модуль отвечающий за вывод панели навигации по статьям
*
*@author Антон Манузин
*@package module
*@version v1.0
*/

class Pagination extends Module{
public function __construct(){
	 parent::__construct();
	 $this->add('url'); //index.php
	 $this->add('urlPage');//index.php?page=
	 $this->add('active');//5
	 $this->add('countElements');//Количество выводимых элементов
	 $this->add('countElementsOnPage');//Количество элементов на странице
	 $this->add('countShowPages');//Число показваемых в навигации страниц
 }
 
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля Pagination(панели навигации по статьям)
*
*@return string
*/
 public function getTmplFile(){
	 return 'pagination';
 }
}
?>