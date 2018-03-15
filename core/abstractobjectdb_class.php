<?php
/**
*Абстрактный класс Ядра  отвечающий за управленеи экземпляром класса AbstrtactDataBase
*
*<ul><li>Загрузка обеькта из БД по его ID</li>
*<li>Инициализация обеькта</li>
*<li>Запись обьекта в базу данных (update||insert)</li>
*<li>Удаление обьекта из БД</li>
*<li>Возвращение все полей в таблице</li>
*<li>Получение количество записей в таблице</li>
*<li>Получение всх значений по определённому полю</li>
*<li>Получение всч записи по какому-то условию с сортировкой и ограничением количества выводимых строк.</li>
*<li>Добавление подобькта в обьект</li>
*<li>Добовлять новые свойства в обект</li>
*<li>Реализует события</li>
*</ul>
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class AbstractObjectDB
{
	/**
	*Метка времени 
	*/
	const TYPE_TIMESTAMP = 1;
	
	/**
	*IP адрес 
	*/	
	const TYPE_IP = 2;
	
	/**
	*@var static array $type Тип данных -  Метка времени или IP адрес или все остольное. Для дальнейшего преобразования значений
	*@access private
	*/
	private static $type = array(self::TYPE_TIMESTAMP, self::TYPE_IP);

	
	/**
	*@var static object $db Обьект базы данных. по умолчанию NULL
	*@access protected
	*/
	protected static $db = null; //Обьект базы данных;
	
	/**
	*@var string $format_date  Формат даты. по умолчанию пустая строка
	*@access private
	*/
	private $format_date = ""; // Формат даты

	/**
	*@var int $id ID обьекта(строки из таблицы). по умолчанию NULL
	*@access private
	*/
	private $id = null;

	/**
	*@var array $properties Массив со свойствами обекта. по умолчанию пустой массив
	*@example Id, Title, ... Название свойст [key =>[type, value]]| [IP=>[2, 452413561243]], [name =>[ ,'Vasya']]
	*@access private
	*/
	protected $properties = array();

	/**
	*@var string $table_name Имя таблицы базы данных. Один обект - одна таблица бд. по умолчанию NULL
	*@access protected
	*/
	protected $table_name = '';
	

	/**
	*Конструктор класса. Инициализирует свойства $table_name, $format_date
	*@param string $table_name таблица БД
	*@param string $format_date формат даты для последующих преобразований
	*@access public
	*@return void
	*/
	public function __construct($table_name, $format_date)
	{
		$this->table_name = $table_name;
		$this->format_date = $format_date;
	}
	
	/**
	*Соединение с БД. Записывает в статическое свойство self::$db парамметр $db
	*@param object $db обьект БД
	*@access public
	*@return void
	*/
	public static function setDB($db)
	{
		self::$db = $db;
	}
	
	/**
	*Загрузка обькта из БД по его Id. Выборка значений из БД. Инициализация значений в обьекте. В случае успеха выполнение события postLoad()
	*Возвращает false если $id < 0, ошибка Select запроса к БД, init($row)  вернул false
	*@param object $db обьект БД
	*@access public
	*@return postLoad($row)
	*/
	public function load($id)
	{
		$id = (int)$id; //Исключение SQL иньекции
		if($id < 0) return false; // проверка на исключение отрицательных значений
		$select = new Select(); //Создаём новй обеькт селект уноследованый от AbstractSelect
		$select->from($this->table_name, $this->getSelectFields())->where("`id` = ".self::$db->getSQ(), array($id)); //Сборка SQL запроса в обьекте селект
		$row = self::$db->selectRow($select); //Выполнение метода по вытаскиванию одной строки из бд
		if(!$row) return false; // если произошли ошибки то завершаем методж
		if($this->init($row)) return $this->postLoad($row); //проводим инициализацию. Если все ОК, то выполняем метод postLoad(событие после загрузки обекта из бд)
	}

	/**
	*Инициализация данных из запроса к БД. Перебирается массив $row. Если тип значения совпадает с  TYPE_TIMESTAMP или TYPE_IP тогда проводятся соответствующие преобразования
	*Результат записывается в properties[$key]['value']. Заполняется свойство $id.
	*@param array $row массив значений
	*@access private
	*@return postInit()
	*/
	protected function init($row)
	{
		foreach($this->properties as $key => $value)
		{

			
			$val = $row[$key]; //из масива запроса вытаскивается заначение ключ которого совпадает с ключем занчения properties
			switch($value['type']) //если тип совпадает с одной из констант то проводяся преобразования
			{
				case self::TYPE_TIMESTAMP:
					if(!is_null($val)) $val = strftime($this->format_date, $val);
					break;
				case self::TYPE_IP:
					if(!is_null($val)) $val = long2ip($val);
					break;
			}
			$this->properties[$key]['value'] = $val; //преобразованые данные зписываются в масив properties
		}
		$this->id = $row['id']; //Устонавливается значение свойства id
		return $this->postInit(); // выполняется событие после инициализации
	}


	/**
	*Проверяет сохранен ли обьект. Возвращает либо id обекта или false
	*@access public
	*@return int|false
	*/	
	public function isSaved()
	{
		return $this->getID() > 0; // если да, то обьект существует
	}

	/**
	*Возвращает ID обьекта
	*@access public
	*@return int
	*/		
	public function getID()
	{
		return (int)$this->id;
	}
	
	/**
	*запись обекта в базу данных (update||insert). Если id обьекта > 0 тогда обновление , иначе вставка.
	*Выполняются события preInsert(), preUpdate(), postUpdate(), postInsert().
	*возвращает false если preInsert(), preUpdate() вернули false
	*@access public
	*@return postUpdate()|postInsert()
	*/
	public function save()
	{
		$update = $this->isSaved();
		if($update) $commit = $this->preUpdate();//если Id > 0 тогда обновление , иначе вставка  
		else $commit = $this->preInsert();
		if(!$commit) return false; //если произошли ошибки тогда false
		$row = array();
		foreach($this->properties as $key=>$value) // Подготовка масива значений $row Для записи в БД
		{
			switch($value['type'])
			{
				case self::TYPE_TIMESTAMP:
					if(!is_null($value['value'])) $value['value'] = strtotime($value['value']);
					break;
				case self::TYPE_IP:
					if(!is_null($value['value'])) $value['value'] = ip2long($value['value']);
					break;
			}
			$row[$key] = $value['value'];
		}
		if(count($row) > 0) //если есть записи тогда:
		{
			if($update)//Обновление записи
			{
				$succes = self::$db->update($this->table_name, $row, "`id =`".self::$db->getSQ(),array($this->getID()));
				if(!$succes) throw new Exception();
				return $this->postUpdate();//???
			}
			else //вставка новой
			{
				$this->id = self::$db->insert($this->table_name, $row);
				if(!$this->getID()) throw new Exception();
				return $this->postInsert();//???
			}
		}
	}

	/**
	*удаление обекта из базы данных.
	*возвращает false если обект не сохранен в бд(isSaved()=false), preDelete()=false
	*@access public
	*@return postDelete()
	*/	
	Public function delete()
	{
		if(!$this->isSaved()) return false;
		if(!$this->preDelete()) return false;
		if(!self::$db->delete($this->table_name, '`id` = '.self::$db->getSQ(),array($this->getID()))) throw new Exception();
		$this->id = null;
		return $this->postDelete();
	}
	
	/**
	*когда записывается новое свойство то проверяется существует ли в массиве $properties такое свойство(имя свойства $name совпадает ли с ключем массива $key),
	*и если есть совпадение то в значение properties[$key]['value'] записывается параметр  $value
	*иначе создается новое свойство в обьекте
	*'$article->title' = dsadasd -> $this->properties['title']['value'] = dsadasd
	*@param string $name имя название нового свойства
	*@param variable $value значение свойства
	*@access public
	*@return void
	*/
	public function __SET($name, $value)
	{
		if(array_key_exists($name, $this->properties)) $this->properties[$name]['value'] = $value;
		else $this->$name = $value; //если токого значение в масиве свойств нету то его запишут как свойство обьекта
	}
	
	/**
	*когда идет обращание к свойству которого нет в классе то проверяется равен ли параметр  $name строке "id". Тогда возвращется getID(). 
	*если существует в массиве $properties такое свойство(имя свойства $name совпадает ли с ключем массива $key).Тогда возвращется properties[$name]['value'].
	*иначе NULL
	*@param string $name имя название нового свойства
	*@access public
	*@return getID()|properties[$name]['value']|NULL
	*/
	public function __GET($name)
	{
		if($name === 'id') return $this->getID();
		return array_key_exists($name, $this->properties)?$this->properties[$name]['value']:null;
	}
	
	/**
	*Двухмерный массив с данными преобразует в массив обектов класса AbstractObjectDB 
	*Ключи возвращаемого массива равны Id обьектов соответсвенно
	*В случае если класса с именем равным $class не существует или обьект класса $class не пренадлежит классу AbstractObjectDB или его наследникам,
	*то возбуждается исключение
	*@param string $class имя класса
	*@param array $data двухмерный массив с данными	
	*@access public
	*@return array
	*/
	public static function buildMultiple($class, $data)
	{
		$ret = array();
		if(!class_exists($class)) throw new Exception();
		$testObj = new $class();
		if(!($testObj instanceof AbstractObjectDB)) throw new Exception();
		unset($testObj);
		foreach ($data as $row){
			$obj = new $class();
			$obj ->init($row);
			$ret[$obj->getID()] = $obj;
		}
		return $ret;
	}
	
	/**
	*Возвращает вся поля в таблице
	*@param int $count количество выводимых записей.
	*@param int $offset смещение вывода	
	*@return getAllWitchOrder($class::$table, $class, 'id', false, $count, $offset)
	*/
	public static function getAll($count = false, $offset = false)
	{
		$class = get_called_class();
		return self::getAllWitchOrder($class::$table, $class, 'id', false, $count, $offset);
	}
	
	/**
	*Возвращает количество записей в таблице 
	*@return getCountOnWhere($class::$table, false, false)
	*/
	public static function getCount()
	{
		$class = get_called_class();
		
		return self::getCountOnWhere($class::$table, false, false);
	}

	/**
	*Возвращает количество записей в таблице по определеному полю с определёным условием
	*@param string $table_name имя таблицы для отбора данных
	*@param string $field поле отбора данных
	*@param string $value подстовляемое значение для  $field
	*@return int
	*/
	public static function getCountOnField($table_name, $field, $value)
	{
		return self::getCountOnWhere($table_name, "`$field` = ".self::$db->getSQ(), array($value));
	}	

	/**
	*Возвращает количество записей в таблице по какому то условию
	*@param string $table_name имя таблицы для отбора данных
	*@param string $where условие отбора данных
	*@param array $value массив данных для подстановки в $where
	*@return int
	*/
	public static function getCountOnWhere($table_name, $where = false, $value = false)
	{
		$select = new Select();
		$select->from($table_name, array('count(id)'));
		if($where) $select->where($where, $value);
		return self::$db->selectCell($select);
	}

	/**
	*Возвращает все поля  в таблице с сортировкой и ограничением количества вывода строк по необходимости
	*@param string $table_name имя таблицы для отбора данных
	*@param string $class имя класса
	*@param string $field имя поля отбора
	*@param string $order строка с критериями сортировки
	*@param bool $desc сортировка по возрастанию или убыванию
	*@param int $count количество выводимых записей.
	*@param int $offset смещение вывода	
	*@param string $value  данные для подстановки в $field(свойство from обьекта select)
	*@todo  проверить работоспособность метода 
	*@return getAllOnWhere($table_name, $class, false, false, $order, $desc, $count, $offset)
	*/	
	public static function getAllOnFieald($table_name, $class, $field, $value, $order = false, $desc = false, $count = false, $offset = false)
	{
		return self::getAllOnWhere($table_name, $class, "`$field` = ".self::$db->getSQ(), array($value), $order, $desc, $count, $offset);
	}
	
	/**
	*Возвращает все поля  в таблице по id
	*@param array $ids значение ID(массив индексов)
	*@return getAllOnIdsField($ids, "id")
	*/	
	public static function getAllOnIds($ids)
	{
		return self::getAllOnIdsField($ids, 'id');
	}
	
	/**
	*Возвращает все поля  в таблице (Выбор по нескольким значениям одного поля). Возвращает масив обектов-строк результата отбора
	*@param array $ids масив значений для условия отбора whereIn($field, $ids);
	*@param string $field имя поля отбора
	*@todo  разобратся как берётся имя таблицы $class::$table
	*@return buildMultiple($class, $data);
	*/		
	public static function getAllOnIdsField($ids, $field)
	{
		$class = get_called_class();
		$select = new Select();
		$select->from($class::$table, "*")//$table_name
			->whereIn($field, $ids);
		$data = self::$db->select($select);
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	/**
	*Возвращает все поля  в таблице по какомуто условию с сортировкой и ограниченым количеством выводимых строк.
	*Если параметр сортировки не задон то сортируется по полю `id` в порядке возрастания
	*Возвращает масив обектов-строк результата отбора
	*@param string $table_name имя таблицы для отбора данных
	*@param string $class имя класса
	*@param string $where условие отбора данных
	*@param array $value массив данных для подстановки в $where
	*@param string $order строка с критериями сортировки
	*@param bool $desc сортировка по возрастанию или убыванию
	*@param int $count количество выводимых записей.
	*@param int $offset смещение вывода	
	*@return buildMultiple($class, $data);
	*/		
	public static function getAllOnWhere($table_name, $class, $where = false, $values = false, $order = false , $desc = false, $count = false , $offset = false)
	{
		$select = new Select();
		$select->from($table_name, '*');
		if($where) $select->where($where, $values);
		if($order) $select->order($order, $desc);
		else $select->order('id');
		if($count) $select->limit($count, $offset);
		$data = self::$db->select($select);
		return AbstractObjectDB::buildMultiple($class, $data);
	}
	
	/**
	*Возвращает все поля  в таблице с сортировкой и ограниченым количеством выводимых строк.
	*Если параметр сортировки не задон то сортируется по полю `id` в порядке возрастания
	*@param string $table_name имя таблицы для отбора данных
	*@param string $class имя класса
	*@param string $order строка с критериями сортировки
	*@param bool $desc сортировка по возрастанию или убыванию
	*@param int $count количество выводимых записей.
	*@param int $offset смещение вывода	
	*@return getAllOnWhere($table_name, $class, false, false, $order, $desc, $count, $offset)
	*/			
	protected static function getAllWitchOrder($table_name, $class, $order = false, $desc = false, $count = false , $offset = false)
	{
		return self::getAllOnWhere($table_name, $class, false, false, $order, $desc, $count, $offset);
	}
	
	/**
	*Добавляет подобьект $class в в свойство $field_out обьектов масива . Возвращает массив обьектов
	*
	*Article->section->title = "test";  echo Article->section->title  => "test"
	*@param array $data массив обьектов
	*@param string $class имя класса
	*@param string $field_out выходное поле Article
	*@param string $field_in входное поле section->title 
	*@return array
	*/			
	protected static function addSubObject($data, $class, $field_out, $field_in)
	{
		$ids = array();
		//получить список всех Id которые нам нужны
		foreach($data as $value)
		{
			$ids[] = self::getComplexValue($value, $field_in); //массив обеькт  $value с свойством/ми  $field_in
		}
		if(count($ids) == 0) return array();//если id нету то возвращаем пустой массив
		$new_data = $class::getAllOnIds($ids); //получаем все обьекты по id(строчки таблицы)
		if(count($new_data) == 0) return $data; // 
		foreach($data as $id=>$value)
		{
			if(isset($new_data[self::getComplexValue($value, $field_in)])) $data[$id]->$field_out = $new_data[self::getComplexValue($value, $field_in)];
			else $value->$field_out = null;
		}
		return $data;
	}
	
	/**
	*Добавляет новые свойства в обект. Свойства может передоватся как отдельное свойство или как цепочка свойств в виде строки с разделителем "->".
	*
	*'Article->section->title' = "test"; echo 'Article->section->title'  => "test"
	*@param object $obj обьект в который необходимо добавить новое свойство
	*@param string $field новые свойства обьекта
	*@return object
	*/		
	protected static function getComplexValue($obj, $field)
	{
		if(strpos($field, '->') !== false)  $field = explode('->', $field);
		if(is_array($field))
		{
			$value = $obj;
			foreach($field as $f) $value = $value->{$f};
		}
		else $value=$obj->$field;
		return $value;
	}

	/**
	*Загружает значения по определенному полю.
	*(Делает выборку из БД. Проводит инициализацию и в случае успеха возвращает postLoad($row) иначе false)
	*@param string $field название поля для отбора
	*@param string $value значение поля для отбора
	*@return postLoad($row)|false
	*/		
	protected function loadOnField($field, $value)
	{
		$select = new Select();
		$select->from($this->table_name, '*')
		->where("`$field = `".self::$db->getSQ(), $value);
		$row = self::$db->selectRow($select);
		if($row)
		{
			if($this->init($row)) return $this->postLoad($row);
		}
		return false;
	}
	
	/**
	*добавление нового значения в двухмерный ассацеативный массив properties.
	*@param string $field имя ключа(свойства) массив properties
	*@param string $validator название класса валидатора на основании которого будет формироватся обект для долнейшей проверки
	*@param int|null $type тип - ip, timestamp или все остальное
	*@param variable $default значение по умолчанию
	*@return void
	*/		
	protected function add($field, $validator, $type = null, $default = null)
	{
		$this->properties[$field] = array('value'=>$default, 'validator'=>$validator, 'type'=>in_array($type, self::$type) ? $type : null);
	}
	/**
	*Возращает имя таблицы класа, который вызвал метод.
	*
	*@return string
	*/
	public static function getTableName(){ 
		
		$class = get_called_class();
		return $class::$table;
	}
	//События
	
	/**
	*События перед вставкой
	*@return validate()
	*/		
	protected function preInsert()
	{
		return $this->validate();
	}
	
	/**
	*События после вставки
	*@return bool
	*/
	protected function postInsert()
	{
		return true;
	}
	
	/**
	*События перед обновлением
	*@return validate()
	*/	
	protected function preUpdate()
	{
		return $this->validate();
	}
	
	/**
	*События после обновлением
	*@return bool
	*/	
	protected function postUpdate()
	{
		return true;
	}
	
	/**
	*События перед удолением
	*@return bool
	*/	
	protected function preDelete()
	{
		return true;
	}
	
	/**
	*События после удоления
	*@return bool
	*/	
	protected function postDelete()
	{
		return true;
	}

	/**
	*События после инициализации
	*@return bool
	*/	
	protected function postInit()
	{
		return true;
	}
	
	/**
	*События перед валидацией
	*@return bool
	*/	
	protected function preValidate()
	{
		return true;
	}
	
	/**
	*События после валидацией
	*@return bool
	*/
	protected function postValidate()
	{
		return true;
	}
	
	/**
	*События после загрузки
	*@return bool
	*/	
	protected function postLoad()
	{
		return true;
	}
	
	/**
	*Получение текущей даты или приведения даты к определённому формату. Если параметр $date = false, бирется текущая дата
	*@param int $date дата в формате количества секунд, прошедших с начала Эпохи Unix (The Unix Epoch, 1 января 1970 00:00:00 GMT) до текущего времени. 
	*@return string
	*/		
	public function getDate($date = false)
	{
		if(!$date) $date = time();
		return strftime($this->format_date, $date);
	}
	
	/**
	*получение номера текущего дня или получение номера дня из конкретной даты. Если параметр $date = false, бирется текущая дата
	*@param string $date текстовое представление даты 
	*@return int
	*/		
	protected static function getDay($date = false)
	{
		if(!$date) $date = time();
		else $date = strtotime($date);
		return date('d', $date);
	}
	
	/**
	*Получение текущего ip
	*@return string
	*/		
	protected function getIP()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
	
	/**
	*Хешировани(добавление к шифруемой страке секретное слово) для эфективного шифрования в md5
	*@param string $str строка для зашифровки 
	*@param string $secret дополнительная секретная строка для усиления шифрования 
	*@return string
	*/	
	protected static function hash($str, $secret = "")
	{
		return md5($str.$secret);
	}
	
	/**
	*Получение Уникальный ключ 
	*@return int
	*/		
		protected function getKey()
	{
		return uniqid();
	}
	
	/**
	*Получение всех полей из $this->properties и добавления к ним поля ID который в $this->properties изночально не находится 
	*@todo проверить принцып работы и необходимость метода
	*@return array
	*/	
	private function getSelectFields()
	{
		$fields = array_keys($this->properties);
		array_push($fields, 'id');
		return $fields;
	}
	
	/**
	*Проверка всех свойств  массива properties на наличие ошибок и их последующая обработка ValidatorException. Есле ошибок нет то возвращается true
	*@return true|экземпляр класса ValidatorException
	*/	
	private function validate()
	{
		if (!$this->preValidate()) throw new Exception(); //если событие перед проверкой вернет false то возбуждаем исключение
		$v = array(); //массив обьектов класса Validator (проверка на каректность)
		$errors = array();//массив ошибок возвращаемых методом getErrors(); обектом класса Validator  
		foreach($this->properties as $key => $value)				
		{
			$v[$key] = new $value['validator']($value['value']); //Создается класса Validator и в конструктор передоется заначение текущего поля 
		}
		foreach($v as $key => $validator)
		{
			if(!$validator->isValid()) $errors[$key] = $validator->getErrors(); // Если метод isValid() у каждого значения $v вернет False тогда в $errors вернется  описание ошибки  возвращаемое методом  getErrors()
		}
		if(count($errors) == 0)
		{
			if(!$this->postValidate()) throw new Exception(); //если массив $errors пуст, но метод postValidate возращает False возбуждаем исключение
			return true; //иначе возращаем истину 
		}
		else throw new ValidatorException($errors); //Если в результате проверки выявились ошибки то возбуждаем исключение ValidatorException и передаем в него $errors
	}
	
}
?>