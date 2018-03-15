<?php
/**
*Модуль отвечающий за вывод заголовка. Блок head
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class Head extends Module{
public function __construct(){
	parent::__construct();
	$this->add("title");
	$this->add("favicon");
	$this->add("meta", null, true);
	$this->add("css");
	$this->add("js");
}

/**
	*Метод добавления значений свойству meta масива properties
	*
	*@param string $name Имя свойства
	*@param string $content Значение атрибута content
	*@param string $httpEequiv Значение атрибута http-equiv
	*@return void
	*/
public function setMeta($name, $content = null, $httpEquiv = false){
	$obj = new stdClass();
	$obj->content = $content;
	$obj->httpEquiv = $httpEquiv;
	$obj->name = $name;
	$this->meta = $obj;
}

/**
	*Метод возращает имя файла шаблона отвечающего за вывод модуля Head(щапки сайта)
	*
	*@return string
	*/
	public function getTmplFile(){
		return "head";
	}
}
?>