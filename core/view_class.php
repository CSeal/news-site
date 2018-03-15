<?php
/**
	*Шаблонизатор. Класс Ядра отсвечающий за подстановку значений в шаблон.
	*
	*Используется буферизация и метод extract() который создает переменые из ключей и значений массива. Массив должен быть ассоциативный.
	*
	*@author Антон Манузин
	*@package core
	*@version v1.0
	*/
class View
{
	/**
	*@var string $tmpl_dir  Имя каталога с шаблонами
	*/
	private $tmpl_dir;
	
	/**
	*Конструктор класса.  Имя каталога с шаблонами $tmpl_dir преданный в конструктор записывается в свойство класа $tmpl_dir.
	*@param string $tmpl_dir Имя каталога с шаблонами
	*@return void
	*/	
	public function __construct($tmpl_dir)
	{
		$this->tmpl_dir = $tmpl_dir;
	}
	
	/**
	*Подставляет значения в шаблон. Используется буферизация и метод extract() который создает переменые из ключей и значений массива. Массив должен быть ассоциативный.
	*
	*@param string $file Имя файла шаблона без учета расширения ".tpl"
	*@param array $param  Ассоциативный массив параметров для подстановки
	*@param bool $return Пврвметр определяющий возвращать ли отрендеренный шаблон или сразу ввыводить в stdout(комманда echo)
	*@return mixed(string|true)
	*/
	public function render($file, $param, $return = true)
	{
		$template = $this->tmpl_dir.$file.".tpl";
		extract($param);
		ob_start();
		require_once($template);
		if($return) return ob_get_clean();
		else 
		{
			echo ob_get_clean();
			return true;
		}
	}
}
?>