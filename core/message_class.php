<?php
/**
*Инициализация и вывод системных сообщений. Класс Ядра работы с системными сообщениями  
*
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
class Message
{
	/**
	*@var array $data  Ассоациативный массив системных сообщений
	*/
	private $data;
	
	/**
	*Конструктор класса. Инициализирует свойства класса. 
	*@param string $file  Путь к файлу с системными сообщениями messages.ini
	*@return void
	*/		
	public function __construct($file)
	{
		$this->data = parse_ini_file($file);
	}
	
	/**
	*Возвращает системное сообщение по его ключу-имени 
	*@param string $name  Ключ-имя к ассациативному массиву c системными сообщениями.
	*@return string
	*/	
	public function get($name)
	{
		return $this->data[$name];
	}
}	
?>