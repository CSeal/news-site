<?php
/**
*Класс, отвечающий за работу с обьктом Section (раздел или секция)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
 class SectionDB extends ObjectDB
 {
	 /**
	*@var string $table Название таблицы
	*/
	 protected static $table = "sections";
	 
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	 public function __construct(){
		 parent::__construct(self::$table);
			$this->add('title', 'ValidateTitle');
			$this->add('img', 'ValidateIMG');
			$this->add('description', 'ValidateText');
			$this->add('meta_desc', 'ValidateMD');
			$this->add('meta_key', 'ValidateMK');
	 }
	 
	/**
	*Обробатывает сабытие пост инициализации. 
	*Если в масиве properties есть свойство относящеесье к картинке статьи и его значение задано, то в значение записывается полный путь к картинке. Возвращается true 
	*Добавляется новое свойство  - URL ссылка на раздел
	*@return boolean
	*/
	 protected function postInit()
	 {
		if(!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
		$this->link = URL::get("section", "", array("id" => $this->id));
		return true;
	 }
	 
	/**
	*Обробатывает сабытие перед валидацией. 
	*Если в масиве properties есть свойство относящеесье к картинке статьи и его значение задано, в значение записывается имя файла картинке с расширением. Возвращается true 
	*
	*@return boolean
	*/
	 protected function preValidate()
	 {
		if(!is_null($this->img)) $this->img = basename($this->img);
		return true;
	 }
 }
?>