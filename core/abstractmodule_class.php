<?php
/**
*Базовый контроллер. Класс Ядра работы с модулями. 
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class AbstractModule
{
	
	/**
	*@var array $properties  Массив модулей
	*/
	public $properties = array();
	
	/**
	*@var Object $view  Обьект шаблонизатора
	*/
	private $view;
	
	/**
	*Конструктор класса. Инициализирует свойства класса. 
	*@param object $view Обьект шаблонизатора
	*@return void
	*/	
	public function __construct($view)
	{
		$this->view = $view;
	}
	
	/**
	*Метод перехватчика. В случае если название запрвшиваемого свойства присутствует в массиве  properties тогда возращается его значение, иначе null
	*
	*@param string $name Имя свойства
	*@return mixed
	*/
	 public function __get($name)
	{
		if(array_key_exists($name, $this->properties)) return $this->properties[$name]['value'];
		return null;
	}
	
	/**
	*Метод перехватчика. В случае если название свойства, для его долнейшего преобразования присутствует в массиве  properties,
	*тогда в массив с ключем value записывается значение параметра $value. В случае неудачи возвращается false
	*
	*@param string $name Имя свойства
	*@param mixed $value Значение свойства
	*@return mixed
	*/
	final public function __set($name, $value)
	{
		if(array_key_exists($name, $this->properties)){
				if(is_array($this->properties[$name]['value']) && !is_array($value)){
					$this->properties[$name]['value'][] = $value;
				}else{
					$this->properties[$name]['value'] = $value;
				};	
		}else{
			return false;
		};
	}

	/**
	*Добавляет в массив properties соответствующего модуля масив с ключами соответсвующими название $field и значением - обьектом $obj
	*
	*@param string $name Имя свойства Модуля
	*@param string $field название ключа масива свойств модля куда добавится переданый обьект
	*@param object $$obj Обьект, который надо добавить
	*@return void
	*/	
	final protected function addObject($name, $field, $obj){
		if(array_key_exists($name, $this->properties)) $this->properties[$name]['value'][$field] = $obj;
	}
	
	/**
	*Добавляет новые свойства(елементы разметки: title, meta, css, js и т.д.) в массив properties соответствующего модуля 
	*
	*@param string $name Имя свойства Модуля
	*@param mixed $default Значение свойства модуля(Значение или массив значений)
	*@param bool $is_array Указывает является ли свойство массивом
	*@return void
	*/	
	final protected function add($name, $default = null, $is_array = false)
	{
		$this->properties[$name]['is_array'] = $is_array;
		if($is_array && $default === null) $this->properties[$name]['value'] = array();
		else $this->properties[$name]['value'] = $default;
	}
	
	/**
	*Возвращает отрендеренный модуль.
	*
	*@return string
	*/	
	final public function __toString(){
		$this->preRender();
		return $this->view->render($this->getTmplFile(), $this->getProperties(), true);
	}
	
	protected function preRender(){}
	/**
	*Возвращает значения очищеных свойств всех модулей(без служебных, вроде 'is_array' и т.д.)
	*
	*@return array
	*/	
	final protected function getProperties()
	{
		$ret = array();
		foreach($this->properties as $name=>$value)
		{
			$ret[$name] = $value['value'];
		}
		return $ret;
	}
	
	/**
	*Обработка цепочки сойств у когонить обькта. 
	*
	*(Article  и User->login->value) У обькта Article бирется заначение свойства User(обект), у которого бирется заначение свойства Login(обект), у которого бирется заначение свойства value и оно же возвращается.
	*@param object $obj обект Модуля
	*@param string $field название свойства или цепочки свойств
	*@return mixed 
	*/	
	final protected function getComplexValue($obj, $field)
	{
		if(strpos("->", $field) !== false) $field = explode("->", $field);
		if(is_array($field))
		{
			$value = $obj;
			foreach($field as $f) $value = $value->{$f};

		}
		else $value = $obj->$field;
		return $value;
	}

	/**
	*обработка подежей при числовых значениях. 
	*
	*Пример: $number = 22, $sufix = array("11"Человек, "2"Человека, "5"Человек). Вернет - "Человека".
	*
	*@param int $number Число Для определения паддежа
	*@param array $sufix Массив уникальных значений для подстановки 
	*@return string 
	*/		
	final protected function numberOf($number, $suffix)
	{
		$keys = array(2, 0, 1, 1, 1, 2);
		$mod = $number % 100;
		$min = min($mod % 10, 5);
		$suffix_key = ($mod > 7 && $mod < 20) ? 2 : $keys[$min];
		return $suffix[$suffix_key];
	}
	
	abstract protected function getTmplFile();
}

?>