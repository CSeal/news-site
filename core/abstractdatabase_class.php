<?php
/**
*Абстрактный класс Ядра отвечающий за создания подключения к базе данных MySQL
*Реализован Паттер Одиночка
*
*<ul><li>Создаёт подключения к базе данных</li>
*<li>Экранирует запросы(Предотврощение SQL инекции)</li>
*<li>Обработка основных запросов SELECT, INSERT, UPDATE, DALETE</li>
*</ul>
*Методы
*<ul><li><b>getSQ</b> - Возвращает символ для замены параметров в запросе к БД</li>
*<li><b>getQuery</b> - Выполняет позднее связывание и экранирует запрос</li>
*<li><b>select</b> - Выполняет выборку нескольких строк из таблицы БД</li>
*<li><b>selectRow</b> - Выполняет выборку одной стрки из таблицы БД</li>
*<li><b>selectCow</b> - Выполняет выборку значений одного столбца из таблицы БД</li>
*<li><b>selectCell</b> - Выполняет выборку значений одной ячейки строки из таблицы БД</li>
*<li><b>insert</b> - Выполняет вставку строки в таблицу БД</li>
*<li><b>update</b> - Выполняет обновление строк в таблицу БД</li>
*<li><b>delete</b> - Выполняет удаление строк из таблицу БД</li>
*<li><b>getTableName</b> - Возвращает имя таблицы с префиксом</li>
*<li><b>query</b> - Обработка(выполнение) запроса  экранированием и подстановкой параметров(кроме SELECT запросов)</li>
*<li><b>getResultSet</b> - Обработка(выполнение) запроса  типа SELECT</li>
*</ul>
*@author Антон Манузин
*@package core
*@version v1.0
*/

abstract class AbstractDataBase
{
	/**
	*@var Object $mysqli Содержит обьект подключения к БД
	*@access protected
	*/
	protected $mysqli;//qqq
	/**
	*@var string $sq Содержит строку замены параметров в зарпросе(для позднего связывания)
	*
	*Содержит строку которой заменяется параметры подстановки в запросе для дальнейшего позднего связывания
	*@example SELECT * FROM 'table1'  WHERE 'par1' = ?, 'par2' = ?;, (arg1, arg2) => SELECT * FROM 'table1'  WHERE 'par1' = 'arg1', 'par2' = 'arg2';
	*
	*@access protected
	*/
	protected $sq;//{?}
	/**
	*@var string $prefix префикс таблиц Базы двнных
	*@access protected
	*/
	protected $prefix;//xqz_
	
	/**
	*Конструктор класса. Создаёт подключение к БД
	*@param string $host Имя хоста подключения
	*@param string $name Имя пользователя обладающего правами записи инф. в БД
	*@param string $password Пароль на подключения к БД
	*@param string $db_name Имя базы данных
	*@param string $sq  Символ для замены параметров в запросе к БД
	*@param string $prefix Префих таблиц БД
	*@access protected
	*@return void
	*/
	protected function __construct($host, $name, $password, $db_name, $sq, $prefix)
	{
		$this->mysqli = mysqli_connect($host, $name, $password, $db_name);
		if($this->mysqli->errno) exit("Ошибка подключения к Базе Даннных");
		$this->sq = $sq;
		$this->prefix = $prefix;
		$this->mysqli->query("SET lc_time_neme = 'uk_UA'");
		$this->mysqli->set_charset("utf8");
	}
	
	/**
	*Возвращает символ для замены параметров в запросе к БД
	*@access public
	*@return AbstrtactDataBase::$sq
	*/
	public function getSQ()
	{
		return $this->sq;
	}
	/*SELECT * FROM 'table1'  WHERE 'par1' = {?}, 'par2' = {?};, (arg1, arg2) =>
     SELECT * FROM 'table1'  WHERE 'par1' = 'arg1', 'par2' = 'arg2';*/
	
	
	/**
	*Выполняет позднее связывание и экранирует запрос.
	*
	*Выполняет позднее связывание переданых параметров с спец символом указаным в {@uses AbstrtactDataBase::$sq}
	*
	*@param string $query Строка запроса к БД
	*@param array $params Масив параметров для замены
	*@access public
	*@return string
	*/
	public function getQuery($query, $params) 
	{
		if($params)
		{
			$offset = 0;
			$len_sq = strlen($this->sq);

			for($i = 0; $i < count($params); $i++)
			{
				$pos = strpos($query, $this->sq, $offset);
				if(is_null($params[$i])) $args = 'NULL';
				else $args = $this->mysqli->real_escape_string($params[$i]);
				$query = substr_replace($query, $args, $pos, $len_sq);
				$offset = $pos + strlen($args);
			}
		}
		return $query;
	}
	
    /**
	*Выполняет выборку нескольких строк из таблицы БД.
	*
	*Выполняет выборку нескольких строк из таблицы БД. 
	*Возвращает  массив содержащий ассоцеативные массивы элементов строк таблицы
	*
	*@param AbstractSelect $select Обект класcа AbstractSelect с сформированным SELECT запросом
	*@access public
	*@return array|false
	*/
	public function select(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, true, true);
		if(!$result_set) return false;
		$array = array();
		while($row = $result_set->fetch_Assoc())
		{
			$array[] = $row;
			
		}
		return $array;
	}
	
	/**
	*Выполняет выборку одной стрки из таблицы БД.
	*
	*@param AbstractSelect $select Обект класcа AbstractSelect с сформированным SELECT запросом
	*@access public
	*@return array|false
	*/
	public function selectRow(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, false, true);
		if(!$result_set) return false;
		return $result_set->fetch_Assoc();
		
	}
	
	/**
	*Выполняет выборку значений одного столбца из таблицы БД.
	*
	*@param AbstractSelect $select Обект класcа AbstractSelect с сформированным SELECT запросом
	*@access public
	*@return array|false
	*/
	public function selectCow(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, true, true);
		if(!$result_set) return false;
		$array = array();
		while($row = $result_set->fetch_Assoc() != false)
		{
			foreach($row as $value)
			{
				$array[] = $value;
				break;
			}
		}
		return $array;
	}
	
	/**
	*Выполняет выборку значений одной ячейки строки из таблицы БД.
	*
	*Если возникли ошибке в запросе, возвращает false
	*
	*@param AbstractSelect $select Обект клаcса AbstractSelect с сформированным SELECT запросом
	*@access public
	*@return variable|false
	*/	
	public function selectCell(AbstractSelect $select)
	{
		$result_set = $this->getResultSet($select, false, true);
		if(!$result_set) return false;
		$arr = array_values($result_set->fetch_Assoc());
		return $arr[0];
	}
	
	/**
	*Выполняет вставку строки в таблицу БД.
	*
	*Если количество элементов в масиве  $row  = 0 возвращает false
	*
	*@param string $table_name имя таблицы БД без префикса
	*@params array $row Асоцеативный массив в котором ключ = имени поля для вставки,
	*а значение масива = значению поля для вставки в БД
	*@access public
	*@return AbstrtactDataBase::query($query, $params)|false
	*/		
	public function insert($table_name, $row)
	{
		if(count($row) == 0) return false;
		$table_name = $this->getTableName($table_name);
		$fields = "(";
		$values = "VALUES(";
		$params = array();
		foreach($row as $key=>$value)
		{
			$fields.= "`$key`,";
			$values.= $this->sq.",";
			$params[] = $value;
		}
		$fields = substr($fields,0,-1);
		$values = substr($values,0,-1);
		$fields.= ")";
		$values.= ")";
		$query = "INSERT INTO $table_name $fields $values ;";
		return $this->query($query, $params);
	}
	
	/**
	*Выполняет обновление строк в таблицу БД.
	*
	*Если количество элементов в масиве  $row  = 0 возвращает false
	*
	*@param string $table_name имя таблицы БД без префикса
	*@param array $row Асоцеативный массив в котором ключ = имени поля для вставки,
	*а значение масива = значению поля для вставки в БД
	*@param string|false $where строка с условием отбора строк для обновления
	*@param array $params масив значений  параметров для $where. По умолчанию пустой
	*@access public
	*@return AbstrtactDataBase::query($query, $params)|false
	*/		
	public function update($table_name, $row, $where = false, $params = array())
	{
		if(count($row) == 0) return false;
		$table_name = $this->getTableName($table_name);
		$query = "UPDATE '$table_name' SET";
		$params_add = array();
		foreach($row as $key=>$value)
		{
			$query.= " '$key' = ".$this->sq;
			$params_add[] = $value;
		}
		if($where)
		{
			$query.= " WHERE $where";
			$params = array_merge($params_add, $params);
		}
		return $this->query($query, $params);
	}
	
	/**
	*Выполняет удаление строк из таблицу БД.
	*
	*@param string $table_name имя таблицы БД без префикса
	*@param string|false $where строка с условием отбора строк для удоления
	*@param array $params масив значений  параметров для $where. По умолчанию пустой
	*@access public
	*@return AbstrtactDataBase::query($query, $params)
	*/	
	public function delete($table_name, $where = false, $params = array())
	{
		$table_name = $this->getTableName($table_name);
		$query = "DALETE FROM '$table_name'";
		if($where) $query.= "WHERE $where";
		return $this->query($query, $params);
		
	}
	
	/**
	*Возвращает имя таблицы с префиксом
	*
	*@param string $table_name имя таблицы БД без префикса
	*@access public
	*@return string
	*/	
	public function getTableName($table_name)
	{
		return $this->prefix.$table_name;
	}
	
	/**
	*Обработка(выполнение) запроса  экранированием и подстановкой параметров(кроме SELECT запросов)
	*
	*Возращает false если запрос завершился с ошибками,
	*true если не связана со вставкой новых записей, 
	*ID новой записи если происходит вставка
	*
	*@param string $query строка запроса 
	*@param array|false $params параметры для подстановки
	*@access private
	*@return bool|int
	*/	
	private function query($query, $params = false)
	{
		$succes = $this->mysqli->query($this->getQuery($query, $params));
		if(!$succes) return false;
		if($this->mysqli->insert_id === 0) return true;
		return $this->mysqli->insert_id;
	}
	
	/**
	*Обработка(выполнение) запроса  типа SELECT
	*
	*Возращает false если запрос завершился с ошибками или
	*если $zero = false и количество строк в результате запроса = 0 или 
	*если $one = false и количество строк в результате запроса = 1
	*Если все прошло удачно возвращает результат запроса
	*
	*@param AbstractSelect $select Обект класа AbstractSelect с сформированным SELECT запросом
	*@param bool $zero может ли запрос вернуть 0 строк 
	*@param bool $one может ли запрос вернуть 1 строку
	*@access private
	*@return object
	*/	
	private function getResultSet(AbstractSelect $select, $zero, $one)
	{
		$resultSet = $this->mysqli->query($select);
		if(!$resultSet) return false;
		if(!$zero  && $resultSet->num_rows == 0) return false;
		if(!$one  && $resultSet->num_rows == 1) return false;
		return $resultSet;
	}
	
		public function __destruct() {
		if (($this->mysqli) && (!$this->mysqli->connect_errno)) $this->mysqli->close();
	}
	
}

?>