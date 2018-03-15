<?php
/**
*Класс Ядра, обрабатывающий POST и GET запросы к серверу
*
<ul><li>Предотвращает XSS атаки а сервер</li>
*<li>Работа с  Request запросами (метод __GET()).</li>
*</ul>
*@author Антон Манузин
*@package core
*@version v1.0
*/
class Request
{
	/**
	*@var array $data Массив обработаных значений Суперглобального масива $_REQUEST($_POST + $_GET)
	*/
	private $data;


	/**
	*Конструктор класса. Обрабатывает весь массив $_REQUEST методом xss() результат запичывает в свойство $data
	*@return void
	*/	
	public function __construct()
	{
		$this->data = $this->xss($_REQUEST);
	}
	
	/**
	*Метод перехватчик _GET. 
	*
	*При обращении $this->request->id, если $_REQUEST['id'], а соотвественно и $this->data['id'] существует, то вернётся this->data['id'] значение.
	*Иначе ничто не возвращается
	*
	*@param string $name название нового свойста
	*@return variable
	*/
	public function __GET($name)
	{
		if(isset($this->data[$name])) return $this->data[$name];
	}
	
	
	/**
	*предотвращает XSS атаки(уберает пробелы вначале и в конце. Преобразует специальные символы в HTML-сущности) 
	*
	*Для обхода массива используется рекурсия
	*
	*@param array $data массив $_REQUEST
	*@return array
	*/	
	private function xss($data)
	{
		if(is_array($data))
		{
			$escaped = array();
			foreach($data as $key => $value)
			{
				$escaped[$key] = $this->xss($value);
			}
			return $escaped;
		}
		return trim(htmlspecialchars($data));
	}
}
?>