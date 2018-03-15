<?php
/**
*Модуль отвечающий за вывод основной части. статьи + навигация
*
*TODO проверить свойства $article->link, sections, category(их нету в базе) 
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class Blog extends Module{
	public function __construct(){
		parent::__construct();
		$this->add('articles', null, true);
		$this->add("pagination");//навигация по статьям 
		$this->add('moreArticles', null, true);
	}

	protected function preRender(){
		foreach($this->articles as $article){
			$article->countComentText = $this->numberOf($article->count_сomments, array("комментарий", "комментария", "комментариев"));
		}
	}	
/**
*Метод двозращает имя файла шаблона отвечающего за вывод модуля Head(Главная секция-> статьи + навигация)
*
*@return string
*/
	public function getTmplFile(){
		return "blog";
	}
}
?>