<?php
/**
*Класс, отвечающий за работу с обьктом Article (Статья)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
class ArticleDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "articles";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@access public
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('title', 'ValidateTitle');
		$this->add('img', 'ValidateIMG');
		$this->add('intro', 'ValidateText');
		$this->add('full', 'ValidateText');
		$this->add('section_id', 'ValidateId');
		$this->add('cat_id', 'ValidateId');
		$this->add('date', 'ValidateDate', self::TYPE_TIMESTAMP, $this->getDay());
		$this->add('meta_desc', 'ValidateMD');
		$this->add('meta_key', 'ValidateMK');
	}
	
	/**
	*Обробатывает сабытие пост инициализации. 
	*Если в масиве properties есть свойство относящеесье к картинке статьи и его значение задано, то в значение записывается полный путь к картинке. Возвращается true 
	*Добавляется новое свойство  - URL ссылка на статью
	*@return boolean
	*/
	protected function postInit()
	{
		if(!is_null($this->img)) $this->img = Config::DIR_IMG_ARTICLES.$this->img;
		$this->link = URL::get("article", "", array("id" => $this->id));
		return true;
	}
	
	/**
	*Обробатывает сабытие перед валидацией. 
	*Если в масиве properties есть свойство относящеесье к картинке статьи и его значение задано, то в значение записывается имя файла картинке с расширением. Возвращается true 
	*
	*@return boolean
	*/
	protected function preValidate()
	{
		if(!is_null($this->img)) $this->img = basename($this->img);
		return true;
	}
	
	/**
	*создание обьктов Секции и категории, загрузка значений на основании идентификатора статьи, сохранение обьектов категории и секции(если они присутствуют в БД) в одноименные свойства обькта статьи. 
	*Создаются новые свойства count_сomments - количество коментариев, day_show = день(числовой вариант, для вывода на страницу), month_show - сокращеная запись нозвания месяца(вывод на страницу);
	*
	*@return void
	*/
	private function setSectionAndCategory()
	{
		$section = new SectionDB();
		$section->load($this->section_id);
		$category = new CategoryDB();
		$category->load($this->cat_id);
		if($section->isSaved()) $this->section = $section;
		if($category->isSaved()) $this->category = $category;
		
	}
	
	/**
	*Обробатывает сабытие пост обработки статьи. 
	*Создаются новые свойства count_сomments - количество коментариев, day_show = день(числовой вариант, для вывода на страницу), month_show - сокращеная запись нозвания месяца(вывод на страницу);
	*
	*@return void
	*/
	private function postHandling()
	{
		$this->setSectionAndCategory();
		$this->count_сomments = CommentDB::getCountOnArticleID($this->id);
		$this->day_show = ArticleDB::getDay($this->date);
		$this->month_show = ArticleDB::getMonth($this->date);
	}
	
	/**
	*Возвращает масив обектов статей которые необходимо вывести.  
	*Статьи сортируются по дате в порядке убывания(Самые свежие статьи вверху).
	*Если необходима пост-обработка статьи(подсчет количества коментариев, список соавторов и т.д.) то указывается флаг $post_handling
	*
	*@param int $count Количество выводимых за раз статей
	*@param int $offset Смещение(блочный вывод)
	*@param boolean $post_handling Флаг указывающий на необхадимость пост-обработки статьи
	*@return array
	*/
	public static function getAllShow($count = false, $offset = false, $post_handling = false)
	{
		$select = self::getBaseSelect();
		$select->order('date', true);
		if($count) $select->limit($count, $offset);
		$data = self::$db->select($select);
		$articles = self::buildMultiple(__CLASS__, $data);
		if($post_handling !== false){
			foreach($articles as $article){
				$article->postHandling();
			};
		};
		return $articles;
	}
	
	/**
	*Возвращает масив обектов статей которые необходимо вывести.  
	*Статьи сортируются по дате в порядке убывания(Самые свежие статьи вверху).
	*
	*@param int $section_id Идентификаир секции(категория 1 уровня)
	*@param int $count Количество выводимых за раз статей
	*@param int $offset Смещение(блочный вывод)
	*@return array
	*/
	public static function getAllOnPageAndSectionId($section_id, $count, $offset = false)
	{
		$select = self::getBaseSelect();
		$select->where("`section_id` = ".self::$db->getSQ(), array($section_id))->order('date', true)->limit($count, $offset);
		$data = self::$db->select($select);
		$articles = self::buildMultiple(__CLASS__, $data);
		foreach($articles as $article) $article->postHandling();
		return $articles;
	}

	/**
	*Возвращает масив обектов всех статей категории. Проводится постаброботка(Добавляется дата создания, ссылка на секцию, категорию)   
	*Статьи сортируются по дате. Если указан параметр Смещение($offset), то статьи будут выводится с со следующего ID и до конца.
	*
	*@param int $cat_id Идентификаир категории
	*@param int $count Количество выводимых за раз статей
	*@param int $order Сртировка по возрастанию или убыванию
	*@param int $offset Смещение(блочный вывод)
	*@return array
	*/
	public static function getOnCatIDWithPostHandling($cat_id, $count, $order = false, $offset){
		
		$select = self::getBaseSelect();
		$select->where('`cat_id` = '.self::$db->getSQ(), array($cat_id))
		->order('date', $order)->limit($count, $offset);
		$data = self::$db->select($select);
		$articles = self::buildMultiple(__CLASS__, $data);
		foreach($articles as $article){
			$article->postHandling();
		};
		return $articles;
	}
	
	/**
	*Возвращает масив обектов всех статей категории.   
	*Статьи сортируются по дате. Если указан параметр Смещение($offset), то статьи будут выводится с со следующего ID и до конца.
	*
	*@param int $cat_id Идентификаир категории
	*@param int $order Сртировка по возрастанию или убыванию
	*@param int $offset Смещение(блочный вывод)
	*@return array
	*/
	public static function getAllOnCatID($cat_id, $order = false, $offset = false)
	{
		return self::getAllSectionOrCategory("cat_id", $cat_id, $order, $offset);
	}
	
	/**
	*Возвращает масив обектов всех статей секции.   
	*Статьи сортируются по дате. Если указан параметр Смещение($offset), то статьи будут выводится с со следующего ID и до конца.
	*
	*@param int $section_id Идентификаир секции
	*@param int $order Сртировка по возрастанию или убыванию
	*@param int $offset Смещение(блочный вывод)
	*@return array
	*/
	public static function getAllOnSectionID($section_id, $order = false, $offset = false)
	{
		return self::getAllSectionOrCategory("section_id", $section_id, $order, $offset);
	}
	
	/**
	*Возвращает масив обектов всех статей секции или категории.   
	*Статьи сортируются по дате. Если указан параметр Смещение($offset), то статьи будут выводится с со следующего ID и до конца.
	*
	*@param string $field название поля для выборки(section_id | cat_id)
	*@param int $value Значение выборки(index)
	*@param int $order Сртировка по возрастанию или убыванию
	*@param int $offset Смещение(блочный вывод)
	*@return array
	*/
	private static function getAllSectionOrCategory($field, $value, $order = false, $offset = false)
	{
		$select = self::getBaseSelect();
		$select->where("`$field` = ".self::$db->getSQ(), array($value))
		->order('date', $order);
		if($offset) $select->limit(-1, $offset);
		$data = self::$db->select($select);
		$articles = self::buildMultiple(__CLASS__, $data);
		return $articles;
	}
	
	/**
	*Переинециализируется обьект текущей статьи (в масив $properties запишутса своиства предшествующий статьи.   
	*Статьи сортируются по дате в порядке убывания.
	*
	*@param object $article_db Обьект текущей статьи
	*@return boolean
	*/
	public function loadPreviusArticle($article_db)
	{
		$select = self::getBaseNeighbourSelect($article_db);
		$select->where("`id` < ".self::$db->getSQ(), array($article_db->id))
		->order("date", true);
		$row = self::$db->selectRow($select);
		return $this->init($row);
	}
	
	/**
	*Переинециализируется обьект текущей статьи (в масив $properties запишутса своиства следующей статьи).   
	*Статьи сортируются по дате.
	*
	*@param object $article_db Обьект текущей статьи
	*@return boolean
	*/
	public function loadNextArticle($article_db)
	{
		$select = self::getBaseNeighbourSelect($article_db);
		$select->where("`id` > ".self::$db->getSQ(), array($article_db->id));
		$row = self::$db->selectRow($select);
		return $this->init($row);
	}
	
	/**
	*Подготавливается Select запрос для последующей выборки соседних статей.   
	*Статьи сортируются по дате.
	*
	*@param object $article_db Обьект текущей статьи
	*@return Object
	*/
	private static function getBaseNeighbourSelect($article_db)
	{
		$select = self::getBaseSelect();
		$select->where("`section_id` = ".self::$db->getSQ(), array($article_db->section_id))
		->order("date")->limit(1);
		return $select;
	}
	
	
	private static function getBaseSelect()
	{
		$select = new Select(self::$db);
		$select->from(self::$table, "*");
		return $select;
	}
}
?>