<?php
/**
*Обработчик форм. Класс Ядра работы с формами. 
*
*Проверяет формы на валидность.
*
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
class FormProcessor
{
	/**
	*@var object $request  Обьект обрапотки URI запросов
	*/
	private $request;
	
	/**
	*@var object $message  Обьект обработки системных сообщений
	*/
	private $message;
	
	/**
	*Конструктор класса. Инициализирует свойства класса. 
	*@param object $request  Обьект класса Request
	*@param object $message  Обьект класса Message
	*@return void
	*/		
	public function __construct($request, $message)
	{
		$this->request = $request;
		
		$this->message = $message;
	}
	
	/**
	*Метод обработки форм.
	*
	*Массив проверок $checks проверяется на корректность, Вслучае неудачи возращается null. Поля (знач.  массива $fields)парсятся и добавляются как свойства обекта  $obj. Если через массив подан метод то он выполняется
	*Идет запись обекта в БД и если все успешно, то в сессию пишется сообщение  об успехе операции и возращается обьект с сохранёнными свойствами. Если не успешно то записываем в сесию сообщение об ошибке и возращаем null.
	*
	*@param string $message_name Имя ключа массива сессии куда будет записыватся сообщение при ошибке(Выбрасыватся исключение)
	*@param object $obj Обьект с которым связана форма. Пример: Форма регистрации пользователя -> обьект класса User
	*@param array $fields Массив названий полей формы или пользовательских констант, для работы с обьектом класса Request; Если $fields[$key] - массив, $fields[$key][0]- имя поля, $fields[$key][1]- значение поля
	*@param array $checks Массив проверок на эквивалент; [0],[1] - Значения для сравнения, [2] - сообщение об ошибке, [3](bool) - определяет тип проверки(Эквивалент || не эквивалент)
	*@param string $success_message Сообщение которое будет выводится при отсутствие ошибок при проверки
	*@return object
	*/
	public function processor($message_name, $obj, $fields, $checks = array(), $success_message = false)
	{

		try
		{
			if(is_null($this->checks($message_name, $checks))) return null;
			foreach($fields as $field)
			{
				if(is_array($field))
				{
					$f_name = $field[0];
					$f_value = $field[1];
					if(strpos($f_name, "()") !== false)
					{
						$f_name = str_replace("()", "", $f_name);
						$obj->$f_name($f_value); //Вызываем метод $f_name с параметром $f_value
					}
					else $obj->$f_name = $f_value;
				}
				else $obj->$field = $this->request->$field;
			}
			if($obj->save())
			{
				if($success_message) $this->setSessionMessage($message_name, $success_message);
				return $obj;
			}
		}
		catch(Exception $e)
		{
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}

	/**
	*Метод проверки на эквивалентность полей форм.
	*
	*Массив проверок $checks проверяется на корректность, В случае неудачи в сессию записывается сообщение об ошибке . возращается null
	*
	*@param string $message_name Имя ключа массива сессии куда будет записыватся сообщение при ошибке(Выбрасыватся исключение)
	*@param array $checks Массив проверок на эквивалент; [0],[1] - Значения для сравнения, [2] - сообщение об ошибке, [3](bool) - определяет тип проверки(Эквивалент || не эквивалент)
	*@return bool
	*/	
	public function checks($message_name, $checks)
	{
		try
		{
			for($i = 0; $i < count($checks); $i++)
			{
				$equal = isset($checks[$i][3]) ? $checks[$i][3] : true;
				if($equal && ($checks[$i][0] !== $checks[$i][1])) throw new Exception($checks[$i][2]);
				elseif(!$equal && ($checks[$i][0] === $checks[$i][1])) throw new Exception($checks[$i][2]);
			}
			return true;
		}
		catch(Exception $e)
		{
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}
	
	/**
	*Метод Записи сообщений в сессию(Неудача||успех).
	*
	*Если не существовала ранее, то создается сессия. В массив $_SESSION['message'] записывается массив с ключом равным именем формы и значением равным сообщениню для записи
	*
	*@param string $to Уникальное имя формы для возврата ошибки
	*@param string $message_name Уникальное имя формы для возврата ошибки
	*@return void
	*/	
	public function setSessionMessage($to, $message)
	{
		if(!session_id()) session_start();
		$_SESSION['message'] = array($to => $message);
	}

	/**
	*Метод Считывает  сообщений из  сессию(Неудача||успех) для формы с именем $to.
	*
	*Если не существовала ранее, то создается сессия. Если массив $_SESSION['message'] не пусто и в $_SESSION['message'][$to] есть запись $_SESSION['message'][$to] удаляется из сессии. !! 
	*
	*@param string $to Уникальное имя формы для возврата ошибки
	*@return this->message->get($message) !!!
	*/
	public function getSessionMessage($to)
	{
		if(!session_id()) session_start();
		if(!empty($_SESSION['message']) && !empty($_SESSION['message'][$to]))
		{
			$message = $_SESSION['message'][$to];
			unset($_SESSION['message'][$to]);
			return $this->message->get($message);
		}
	}
	
	/**
	*Метод Загрузка картинок на сервер.
	*
	*@param string $message_name Уникальное имя формы
	*@param array $file Супер глобальный массив $_FILE
	*@param int $max_size Максимальный размер файла
	*@param string $dir Каталог для сахранения файла на сервере
	*@param bool $source_name Файл будет сохранен на сервер под своим локальным именем или будет сгенерировано уникальное
	*@return bool 
	*/
	public function uploadIMG($message_name, $file, $max_size, $dir, $source_name = false)
	{
		try
		{
			$name = FILE::uploadIMG($file, $max_size, $dir, false, $source_name);
			return true;
		}
		catch(Exception $e)
		{
			$this->setSessionMessage($message_name, $this->getError($e));
			return false;
		}
	}
	
	/**
	*Метод возращается текст ошибки. Если в обьект $e не передан тектс ошибки то возращается строка "UNKNOWN_ERROR"
	*
	*@param object $e Экземпляр класса Exception
	*
	*@return string 
	*/
	private function getError($e)
	{
		if($e instanceof ValidatorException)
		{
			$errors = current($e->getErrors());
			return $errors[0];
		}
		elseif(($message = $e->getMessage())) return $message;
		else return "UNKNOWN_ERROR";
	}
}
?>