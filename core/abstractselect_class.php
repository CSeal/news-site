<?php
/**
*Класс Ядра  отвечающий за формирование обьекта SELECT запроса
*
*<ul><li>Формирует основный предикаты FROM, WHERE, ORDER, LIMIT</li>
*</ul>
*@todo Должен ли класс быть абстрактным ?!!
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class AbstractSelect
{
	/**
	*@var Object $db Содержит обьект подключения к БД
	*@access private
	*/
	private $db;
	
	/**
	*@var string $from Содержит часть строки запроса SELECT - FROM(Поля выборки + имя таблицы)
	*
	*@access private
	*/
	public $from = '';
	
	/**
	*@var string $where Содержит часть строки запроса SELECT - WHERE(Условия по каким идет отбор)
	*
	*@access private
	*/
	private $where = '';
	
	/**
	*@var string $order Содержит часть строки запроса SELECT - ORDER(сортировка по полю в порядки возрастания или убывания)
	*
	*@access private
	*/
	private $order = '';
	
	/**
	*@var string $limit Содержит часть строки запроса SELECT - LIMIT(ограничение количества выводимого результата)
	*
	*@access private
	*/
	private $limit = '';
	
	/**
	*Конструктор класса. Создаёт подключение к БД
	*@param object $db обект подключения к БД
	*@access public
	*@return void
	*/
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	/**
	*Формирует свойство $from. Возращает сам обект(Сам себя для последующевызова цепочки WHERE->ORDER->LIMIT)
	*@param string $tableName Имя таблицы без префикса
	*@param string|array $fields поля отбора(STRING - если надо выбрать все значения)
	*@access public
	*@return object AbstractSelect
	*/
	public function from($tableName, $fields)
	{
		$tableName = $this->db->getTableName($tableName);
		$from = '';
		if($fields == '*') $from = '*';
		else
		{
			for($i = 0; $i < count($fields); $i++)
			{
				if(($pos_1 = strpos($fields[$i],'(')) !== false)
				{
					$pos_2 = strpos($fields[$i],')');
					$from.= substr($fields[$i], 0, $pos_1).'(`'.substr($fields[$i],$pos_1 + 1, $pos_2 - $pos_1 - 1).'`),';
				}
				else
				{
					$from.= "`".$fields[$i]."`,";
				}
			}
			$from = substr($from, 0, -1);//Убираем последнюю запятую
		}
		$this->from = $from." FROM `$tableName`";
		return $this;
	}
	
	/**
	*Формирует свойство $where. Возращает сам обект(Сам себя для последующевызова цепочки ORDER->LIMIT)
	*@param string $where строка условия выборки в запросе 
	*@param array $values массив значений параметров для подстановки в $where. По умолчанию пустой массив
	*@param bool $and условие связки условий выборки. Если true то AND, иначе OR. По умолчанию true	
	*@access public
	*@return object AbstractSelect
	*/
	public function where($where, $values = array(), $and = true)
	{
		if($where)
		{
			$where = $this->db->getQuery($where, $values);
			$this->addWhere($where, $and);
		}
		return $this;
	}
	
	/**
	*Формирует свойство $where когда полю $field соответсвует несколько значений. Возращает сам обект(Сам себя для последующевызова цепочки ORDER->LIMIT)
	*@param string $field поле  отбора
	*@param array $values массив значений параметров для подстановки в $where
	*@param bool $and условие связки условий выборки. Если true то AND, иначе OR. По умолчанию true	
	*@access public
	*@return AbstractSelect::where($where, $values, $and)
	*/
	public function whereIn($field, $values, $and = true)
	{
		$where = "`$field` IN (";
		foreach($values as $value)
		{
			$where.= $this->db->getSQ().',';
		}
		$where = substr($where, 0, -1);
		$where.= ')';
		return $this->where($where, $values, $and);
	}
	
	/**
	*Формирует свойство $where которое возвращает номер позиции вхождения строки $value в значение столбца $col_name
	*Нулевые значения отбрасываются
	*@param string $col_name поле  в котором надо проверить присутствия значения $value
	*@param string $value значение проверки
	*@param bool $and условие связки условий выборки. Если true то AND, иначе OR. По умолчанию true	
	*@access public
	*@return object
	*/
	public function whereFIS($col_name, $value, $and = true)
	{
		$where = 'FIND_IN_SET('.$this->db->getSQ().", `$col_name`) > 0";
		return $this->where($where, array($value), $and);
	}
	
	
	/**
	*Формирует свойство $order. Сортируется по полю $fields(Может быть масивом значений). Если $desc = true, то сортировка идет в порядке убывания. Возращает сам обект(Сам себя для последующевызова цепочки LIMIT)
	*@param string|array $fields поле  сортировки
	*@param bool $desc сортировка в обратном или прямом порядке.По умолчанию false	
	*@access public
	*@return object AbstractSelect
	*/
	public function order($fields, $desc = false)
	{
		if(is_array($fields))
		{
			$this->order = ' ORDER BY ';
			if(!is_array($desc))
			{
				$temp = array();
				for($i = 0; $i < count($fields); $i++) $temp[] = $desc;
				$desc = $temp;
			}
			for($i = 0; $i < count($fields); $i++)
			{
				$this->order.= ' `'.$fields[$i].'`';
				if($desc[$i]) $this->order.= ' DESC,';
				else $this->order.= ',';
			}
			$this->order = substr($this->order, 0, -1);	
		}
		else
		{
			$this->order = " ORDER BY `$fields`";
			if($desc) $this->order.= ' DESC';
		}
		return $this;
	}
	
	/**
	*Формирует свойство $order. случайная сортировка
	*@access public
	*@return void
	*/
	public function orderRand()
	{
		$this->order = 'ORDER BY RAND()';
		return $this;
	}
	
	/**
	*Формирует свойство $limit. Ограничивает количество выводимой за рас информации в результате SELECT запроса. Возращает сам обект
	*@param int $limit количество выводимых строчек запроса
	*@param int $offset Смещение. По умолчанию = 0	
	*@access public
	*@return object AbstractSelect
	*/
	public function limit($limit, $offset = 0)
	{
		$limit = (int)$limit;
		$offset = (int)$offset;
		if($limit < 0 || $offset < 0) return false;
		$this->limit = "LIMIT $offset, $limit";
		return $this;
	}
	
	/**
	*Магический метод преобразует обект класса AbstractSelect в строку SELECT запроса. Если свойство $from обекта не заполнено - вернет пустую строку. 
	*@access public
	*@return string
	*/
	public function __toString()
	{
		if($this->from) return 'SELECT '.$this->from.' '.$this->where.' '.$this->order.' '.$this->limit;
		else return '';
	}
	
	/**
	*Формирует свойство $where c параметрами связки AND|OR. 
	*Если в экземпляре класса AbstractSelect свойство $where заполнено то к ниму конкатенируется  $where переданное парамметром в метод с учетом парамметра $and.
	*Иначе в свойство $where записывается значение парамметра $where без учета условия $and
	*@param string $where строка условия выборки в запросе 
	*@param bool $and условие связки условий выборки. Если true то AND, иначе OR.	
	*@access private
	*@return object AbstractSelect
	*/
	private function addWhere($where, $and)
	{
		if($this->where)
		{
			if($and) $this->where.= ' AND ';
			else $this->where.= ' OR ';
			$this->where.= $where;
		}else $this->where = "WHERE $where";
	}
}
?>