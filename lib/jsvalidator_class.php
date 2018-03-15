<?php
class JSValidator{
	/**
	*Клас для валидации полей форм на стороне клиента(jsValidator). Добавляет к полям формы атрибуты для проверки.	
	*
	*В конструктор передается обьект класа Message.
	*
	*@author Антон Манузин
	*@package ./lib/
	*@version v1.0
	*/
	
	/**
	*@var object $message Содержит оюбект класса Message для вывода системных сообщений из
	*message.ini файла
 	*@access private
	*/
	private $message;
	
	public function __construct($message){
		$this->message = $message;
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами проверки Пароля
	*
	*@param string|boolean $t_empty Может ли быть поле пустым. В случае не соответствия выводится сообщение об ошибке(STRING - выводится это сообщение, false = проверка не нужна, "" = выводится системное сообщение)
	*@param boolean $min_len Минимальная длина поля
	*@param boolean $max_len Максимальная длина поля
	*@param string|boolean $f_equial Имя эквивалентного(зависящего) поля
	*@return object 
	*/
	public function password($t_empty = false, $min_len = true, $max_len = true, $f_equial = false){
		
		return $this->getBaseData($t_empty, $min_len, $max_len, "password", "ValidatePassword", false, $f_equial);
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами проверки Имени
	*
	*@param string|boolean $t_empty Может ли быть поле пустым. В случае не соответствия выводится сообщение об ошибке(STRING - выводится это сообщение, false = проверка не нужна, "" = выводится системное сообщение)
	*@param boolean $min_len Минимальная длина поля
	*@param boolean $max_len Максимальная длина поля
	*@param string|boolean $t_type Сообщение при нахождении ошибки проверки на корректность ввода. Ечли передана false- выводится системная ошибка  
	*@return object 
	*/
	public function name($t_empty = false, $min_len = false, $max_len = false, $t_type = false){
		return $this->getBaseData($t_empty, $min_len, $max_len, "name", "ValidateName", $t_type);
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами проверки Логина
	*
	*@param string|boolean $t_empty Может ли быть поле пустым. В случае не соответствия выводится сообщение об ошибке(STRING - выводится это сообщение, false = проверка не нужна, "" = выводится системное сообщение)
	*@param boolean $min_len Минимальная длина поля
	*@param boolean $max_len Максимальная длина поля
	*@param string|boolean $t_type Сообщение при нахождении ошибки проверки на корректность ввода  согласно указаного типа поля
	*@return object 
	*/
	public function login($t_empty = false, $min_len = false, $max_len = false, $t_type = false){
		return $this->getBaseData($t_empty, $min_len, $max_len, "login", "ValidateLogin", $t_type);
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами проверки Емайл
	*
	*@param string|boolean $t_empty Может ли быть поле пустым. В случае не соответствия выводится сообщение об ошибке(STRING - выводится это сообщение, false = проверка не нужна, "" = выводится системное сообщение)
	*@param boolean $min_len Минимальная длина поля
	*@param boolean $max_len Максимальная длина поля
	*@param string|boolean $t_type Сообщение при нахождении ошибки проверки на корректность ввода  согласно указаного типа поля
	*@return object 
	*/
	public function email($t_empty = false, $t_type = false){
		return $this->getBaseData($t_empty, false, false, "email", "ValidateEmail", $t_type);
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами проверки аватарки
	*
	*@return object 
	*/
	public function avatar(){
		$cl = $this->getBaseCl();
		$cl->t_empty = $this->message->get("ERROR_AVATAR_EMPTY");
		return $cl;
	}
	
	/**Запалняет обьект, созданый методом getBaseCl, свойствами проверки каптчи
	*
	*@return object 
	*/
	public function captcha(){
		$cl = $this->getBaseCl();
		$cl->t_empty = $this->message->get("ERROR_CAPTCHA_EMPTY");
		return $cl;
	}
	
	/**
	*Запалняет обьект, созданый методом getBaseCl, свойствами
	*
	*@param string|boolean $t_empty Сообщение при нахождении ошибки проверки на незаполненость поля
	*@param boolean $min_len Минимальная длина поля
	*@param boolean $max_len Максимальная длина поля
	*@param string|boolean $type Тип поля email, password и т.д.
	*@param string $class Имя класса-валидатора
	*@param string|boolean $t_type Сообщение при нахождении ошибки проверки на корректность ввода  согласно указаного типа поля
	*@param string|boolean $f_equial Имя эквивалентного(зависящего) поля
	*@return object 
	*/
	private function getBaseData($t_empty, $min_len, $max_len, $type, $class, $t_type, $f_equal = false){
		$cl = $this->getBaseCl();
		if($type) $cl->type = $type;
		if(is_string($t_empty)) $cl->t_empty = $t_empty;
		elseif(is_null($t_empty)) $cl->t_empty = $this->message->get($class::CODE_EMPTY);
		if(is_int($min_len)){
			$cl->min_len = 	$min_len;
			$cl->t_min_len = $this->message->get($class::CODE_MIN_LEN);
		}elseif(is_null($min_len)){
			$cl->min_len = 	$class::MIN_LEN;
			$cl->t_min_len = $this->message->get($class::CODE_MIN_LEN);
		};
		if(is_int($max_len)){
			$cl->max_len = 	$max_len;
			$cl->t_max_len = $this->message->get($class::CODE_MAX_LEN);
		}elseif(is_null($max_len)){
			$cl->max_len = 	$class::MAX_LEN;
			$cl->t_max_len = $this->message->get($class::CODE_MAX_LEN);
		}
		if(is_string($t_type)) $cl->t_type = $t_type;
		else{ 
		$cl->t_type = $this->message->get($class::CODE_INVALID);
		}
		if(is_string($f_equal)){
			$cl->f_equal = $f_equal;
			$cl->t_equal = $this->message->get($class::CODE_EQUIAL);
		}
		return $cl;
	}
	/**
	*Создает обьект стандартного класа для хранения параметров для проверки и возращаемых сообщениях об ошибках
	*
	*@return object 
	*/
		private function getBaseCl(){
		$cl = new stdClass(); //Создаем стандартный класс
		$cl->type = ""; //Тип поля email, password и т.д.
		$cl->min_len = ""; // Минимальная длина поля
		$cl->t_min_len = "";// Сообщение при нахождении ошибки проверки минимальной длины поля
		$cl->max_len = "";// Максимальная длина поля
		$cl->t_max_len = "";// Сообщение при нахождении ошибки проверки максимальной длины поля
		$cl->t_empty = "";// Сообщение при нахождении ошибки проверки на незаполненость поля
		$cl->t_type = ""; //Сообщение при нахождении ошибки проверки на корректность ввода  согласно указаного типа поля
		$cl->f_equal = ""; //Имя эквивалентного(зависящего) поля
		$cl->t_equal = ""; //Сообщение при нахождении ошибки проверки  на совпадение с эквивалентным полем		
		
		return $cl;
	}
	
}

?>