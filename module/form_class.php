<?php
/**
*Модуль отвечающий за вывод Формы. Форма ввода
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class Form extends BreadcrumbsModule{
	public function __construct(){
		parent::__construct();
		$this->add("name");//Имя формы
		$this->add("action");//Урл куда пересылать данные
		$this->add("method", "POST");//Метод отправки данных на сервер
		$this->add("enctype");//Указывается, если в форме необхадимо пердовать файлв
		$this->add("inputs", null, true);//Масссив элементов ввода, которые могут встречатся в форме
		$this->add("jsv", null, true);//Массив обьектов для валидации формы на javascript
		$this->add("header");//Загаловок Формы(то, что выводится в DIV-е)
		$this->add("check", true);//Маркер, надо ли форму проверять
		$this->add("message");// Присутствует ли приветствие
	}
	
/**
*Метод добавления обьектов отвечающий за валидацию(JavaScript) полей ввода формы в масив jsv массива properties
*
*@param string $field Имя поля ввода
*@param object $jsv JavaScript валидатор
*@return void
*/
	public function addJsv($field, $jsv){
		$this->addObject("jsv", $field, $jsv);
	}

/**
*Метод добавления обьекта со свойствами поля ввода текста формы в массив inputs
*
*@param string $name Имя поля ввода текста
*@param string $label Подпись поля ввода текста
*@param string $value Значение поля ввода текста
*@param string $defValue Значение по умолчанию поля ввода текста(placeholder) 
*@return void
*/	
	public function text($name, $label = "", $value = "", $defValue = ""){
		$this->addInput($name, "text", $label, $value, $defValue);
	}
	
/**
*Метод добавления обьекта со свойствами поля ввода пароля формы в массив inputs
*
*@param string $name Имя поля ввода пароля
*@param string $label Подпись поля ввода пароля
*@param string $defValue Значение по умолчанию поля ввода пароля(placeholder) 
*@return void
*/	
	public function password($name, $label = "", $defValue = ""){
		$this->addInput($name, "password", $label, "", $defValue);
	}

/**
*Метод добавления обьекта со свойствами поля каптчи формы в массив inputs
*
*@param string $name Имя поля каптчи
*@param string $label Подпись поля каптчи
*@return void
*/	
	public function captcha($name, $label){
		$this->addInput($name, "captcha", $label, "", "");
	}

/**
*Метод добавления обьекта со свойствами поля формы загрузки картинки(аватарки) на сервер  в массив inputs
*
*@param string $name Имя поля загрузки картинки
*@param string $label Подпись поля загрузки картинки
*@param string $img Значение поля загрузки картинки
*@return void
*/	
	public function fieldImg($name, $label = "", $img){
		$this->addInput($name, "file_img", $label, $img, "");
	}

/**
*Метод добавления обьекта со свойствами скрытого поля формы в массив inputs
*
*@param string $name Имя скрытого поля
*@param string $value Значение скрытого поля
*@return void
*/	
	public function hidden($name, $value){
		$this->addInput($name, "hidden", "", $value, "");
	}

/**
*Метод добавления обьекта, со свойствами кнопки отправки данных формы на сервер, в массив inputs
*
*@param string $name кнопки отправки данных формы
*@param string $value Значение кнопки отправки данных формы
*@return void
*/	
	public function submit($name, $value){
		$this->addInput($name, "submit", "", $value, "");
	}

/**
*Метод добавления обьекта со свойствами соответствующего поля ввода формы в массив inputs
*
*@param string $name Имя поля ввода
*@param string $type Тип поля ввода 
*@param string $label Подпись поля ввода
*@param string $value Значение поля ввода
*@param string $defValue Значение по умолчанию поля ввода (placeholder) 
*@return void
*/	
	private function addInput($name, $type, $label, $value, $defValue){
		$cl = new stdClass();
		$cl->name = $name;
		$cl->type = $type;
		$cl->label = $label;
		$cl->value = $value;
		$cl->defValue = $defValue;
		$this->inputs = $cl;
	}
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля Form(форма ввода)
*
*@return string
*/	
	public function getTmplFile(){
		return "form";
	}
}
?>