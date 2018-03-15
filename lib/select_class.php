<?php
/**
*Адаптер Абстрактного класс Ядра AbstractSelect(отвечает за формирование обьекта SELECT запроса)
*Расширяет класс Ядра; Передает в родительский когнструктор статической свойство класа DataBase $db содержащее обьект базе данных MySQL
*
*@author Антон Манузин
*@package lib
*@version v1.0
*/
class Select extends AbstractSelect
{
	/**
	*Конструктор класса. Передает в родительский когнструктор статической свойство класа DataBase $db содержащее обьект базе данных MySQL
	*@access public
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(DataBase::getDBO());
	}
}
?>