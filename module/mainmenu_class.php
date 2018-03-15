<?php
/**
*Модуль отвечающий за вывод Главного меню(Сбоку с лева). Блок mainmenu
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class MainMenu extends Module{
 public function __construct(){
	 parent::__construct();
	 $this->add("uri");
	 $this->add("items", null, true);//масив всех элементов меню(в него встраиваются все свойства из MenuDB<-ObjectDB<-AbstractObjectDB)$this->add("items")
 }
 
/**
*Метод Создает два добавачных масива childrens и active для создания каскада меню и выделения головных разделов как активные
*
*@return void
*/
 protected function preRender(){
	 $this->add("childrens", null, true);//масив, ключи которого - айдишники элементов меню, у которых есть родители(тоесть являются подменю). а значение - айдишники родителей(чьйм подменю они являются)
	 $this->add("active", null, true);//массив, значения которого - айдишники элементов активного меню, включая подменю(каскад)
	 $childrens = array();
	foreach($this->items as $item){
		 if($item->parent_id){
			 $childrens[$item->id] = $item->parent_id; 
		 };
	 }
	 $this->childrens = $childrens;
	 $active = array();
	 foreach($this->items as $item){
		 if($item->link === URL::deletePage($this->uri)){
			 $active[] = $item->id;
			 if($item->parent_id){
					 $parent_id = $item->parent_id;
				 $active[] = $parent_id;
				 while($parent_id){
					 $parent_id = $this->items[$parent_id]->parent_id;
					 if($parent_id) $active[] = $parent_id;
				 }
			 };	 
		 }
	 }
	 $this->active = $active;
 }
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля MainMenu(Главного меню(Сбоку с лева))
*
*@return string
*/
 protected function getTmplFile(){
	 return "mainmenu";
 }
}
?>