<?php
/**
*Клас отвечающий  зв вывод опросов. Блок Poll
*
*@author Антон Манузин
*@package module
*@version v1.0
*/

class Poll extends Module{
	public function __construct(){
		parent::__construct();//обьект класа PollDB
		$this->add("data");// массив обьектов класа PollDataDB
		$this->add("action");//куда форма должна передовать данные опроса 
		$this->add("title"); // Помоему есть в родительском классе
	}
	
	/**
	*Метод возращает имя файла шаблона отвечающего за вывод модуля Poll(Опросы)
	*
	*@return string
	*/
	public function getTmplFile(){
		return "poll";
	}
}
?>