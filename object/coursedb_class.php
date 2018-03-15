<?php
/**
*Класс, отвечающий за загрузку банеров курсов CourseDB (курсы)
*
*@author Антон Манузин
*@package object
*@version v1.0
*/
class CourseDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "courses";
	
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@access public
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('type', 'ValidateCourseType');
		$this->add('header', 'ValidateTitle');
		$this->add('sub_header', 'ValidateTitle');
		$this->add('img', 'ValidateIMG');
		$this->add('link', 'ValidateURL');
		$this->add('text', 'ValidateText');
		$this->add('did', 'ValidateId');//Идентификатор рассылки
		$this->add('latest', 'ValidateBoolean');
		$this->add('section_ids', 'ValidateIDs');
	}
	
	/**
	*Обробатывает сабытие пост инициализации. 
	*Если в масиве properties есть свойство относящеесье к картинке статьи и его значение задано, то в значение записывается полный путь к картинке. Возвращается true 
	*Добавляется новое свойство  - URL ссылка на статью
	*@return boolean
	*/
	protected function postInit()
	{
		$this->img = Config::DIR_IMG_ARTICLES.$this->img;
		return true;
	}
	
	/**
	*Делает выборку из бвзы курсы на основании приоритетности типов(1,2,3), важности(latest), принадлежности определённой секции. 
	*Проводится загрузка (инициализация) первого обьекта из масива выбраных и отсортированых обьектов.  
	*
	*@todo Реализовать метод whereFIS(отбор значений БД через регулярные вырожения)
	*@param int $section_id идентификатор пренадлежности определенной секции
	*@param int $type тип курса
	*@return void
	*/
	public function loadOnSectionId($section_id, $type)
	{
		$select = new Select(self::$db);
		$select->from(self::$table, '*')
			   ->where('type = '.self::$db->getSQ(), array($type))
				->where('latest = '.self::$db->getSQ(), array(1))
			   ->orderRand();
		$data_1 = self::$db->select($select);
		$select = new Select(self::$db);
		$select->from(self::$table, '*')
			   ->where('type ='.self::$db->getSQ(), array($type));
		if($section_id) $select->whereFIS('section_ids', $section_id)->orderRand();
		$data_2 = self::$db->select($select);
		$data = array_merge($data_1, $data_2);
		if(count($data) == 0)
		{
			$select = new Select(self::$db);
			$select->from(self::$table, '*')
				   ->where('type ='.self::$db->getSQ(), array($type))
				   ->orderRand();
			$data = self::$db->select($select);
		}
		$data = self::buildMultiple(__CLASS__, $data);
		uasort($data, array(__CLASS__, 'compare'));
		$this->load(array_shift($data)->id);
	}
	
	/**
	*Пользовательская CALLBACK функция для сортировки массива обьктов курсов. 
	*Приорететность сортировки - Сперва Важность (latest), в случае если они совпадают - Приоретитность типов (1 - самый приоритетный)  
	*
	*@param object $value_1  первый элемент массива для сравнения
	*@param object $value_2  второй элемент массива для сравнения
	*@return int
	*/
	private function compare($value_1, $value_2)
	{
		if($value_1->latest != $value_2->latest) return ($value_1->latest > $value_2->latest)? 1 : -1;
		else
		{
			if($value_1->type == $value_2->type) return 0;
			else return  ($value_1->type < $value_2->type)? 1 : -1;
		}
	}
}
?>