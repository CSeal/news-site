<?php
/**
*Модуль отвечающий за отрисовку формы авторизации пользователя
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class Auth extends Module{
	public function __construct(){
		parent::__construct();
		$this->add('action');
		$this->add('message');//Сообщения об ошибках
		$this->add('linkRegister');
		$this->add('linkReset');
		$this->add('linkRemind');
	}
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля Auth(авторизация пользователя)
*
*@return string
*/	
	public function getTmplFile(){
		return 'auth';
	}
}
?>