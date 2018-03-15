<?php
/**
*Класс выводящий превью-рекламу(банеры) курсов.В даннос случае выводится один курс отобраный по какимто критериям(Изначально задумывался как карусель). Связан с класом CourseDB;
*
*@package module
*@version v1.0
*/
class Slider extends Module{
	public function __construct(){
		parent::__construct();
		$this->add("course");
	}
	
	/**
	*Метод возращает имя файла шаблона отвечающего за вывод модуля Slider(Центральный банер курсов)
	*
	*@return string
	*/
	public function getTmplFile(){
		return "slider";
	}
}
?>