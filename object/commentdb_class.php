<?php
/**
*Класс, отвечающий за работу с обьктом Comment(коментарии)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
 class CommentDB extends ObjectDB
 {
	 /**
	*@var string $table Название таблицы
	*/
	 protected static $table = "comments";
	 
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	 public function __construct(){
		 parent::__construct(self::$table);
			$this->add('article_id', 'ValidateId');
			$this->add('user_id', 'ValidateId');
			$this->add('parent_id', 'ValidateId');
			$this->add('text', 'ValidateSmallText');
			$this->add('date', 'ValidateDate', self::TYPE_TIMESTAMP, $this->getDay());
	 }
	 
	/**
	*Обробатывает сабытие пост инициализации. 
	*Добавляется новое свойство  - URL ссылка на раздел с якорем на коментарий пользователя
	*Возвращается true 
	*@return boolean
	*/
	 protected function postInit()
	 {
		$this->link = URL::get("article", "", array("id" => $this->article_id));
		$this->link = URL::addId($this->link, "comment_".$this->id);
		return true;
	 }
	 
	/**
	*Возвращается все коментарии статьи в виде масива обьктов. Добавляет обьект с информацией о пользователе кторый добавел комент  
	*
	*@param int $article_id Идентификатор статьи
	*@return array
	*/
	 public static function getAllOnArticleID($article_id)
	 {
		 $select = new Select(self::$db);
		 $select->from(self::$table, "*")
		 ->where('`article_id` = '.self::$db->getSQ(), array($article_id))
		 ->order('date');
		 $comments = self::buildMultiple(__CLASS__, self::$db->select($select));
		 $comments = self::addSubObject($comments, "UserDB", "user", "user_id");
		 return $comments;
	 }
	/**
	*Возвращает количество коментариев по идентификатору статьи
	*
	*@param int $article_id Идентификатор статьи
	*@return int
	*/
	 public static function getCountOnArticleID($article_id)
	 {
		 $select = new Select(self::$db);
		 $select->from(self::$table, array('count(article_id)'))->where("`article_id` = ".self::$db->getSQ(), array($article_id));
		 return self::$db->selectCell($select);
	 }
 }
?>