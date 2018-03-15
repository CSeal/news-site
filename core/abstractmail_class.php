<?php
/**
*Отправка электронных писем. Абстрактный класс Ядра   
*
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class AbstractMail
{
	/**
	*@var object $view  Экземпляр класса View для обработки шаблона письма
	*/
	private $view;
	
	/**
	*@var string $from  С кокого адреса идетка отправка письма
	*/
	private $from;
	
	/**
	*@var string $from_name  Имя отправителя письма. Поумолчанию равен пустой строке
	*/
	private $from_name = "";
	
	/**
	*@var string $type  Тип отпраляемого письма. По умолчанию text/html
	*/
	private $type = "text/html";
	
	/**
	*@var string $encoding  Кодировка отпраляемого письма. По умолчанию utf-8
	*/
	private $encoding = "utf-8";
	
	/**
	*Конструктор класса. Инициализирует свойства класса. 
	*@param string $from  С кокого адреса идетка отправка письма
	*@param object $view  Экземпляр класса View для обработки шаблона письма
	*@return void
	*/		
	public function __construct($from, $view)
	{
		$this->from = $from;
		$this->view = $view;
	}
	
	/**
	*Устонавливает свойство $from. 
	*@param string $from  Тип отпраляемого письма
	*@return void
	*/	
	public function setFrome($from)
	{
		$this->from = $from;
	}
	
	/**
	*Устонавливает свойство $from_name. 
	*@param string $from_name  Имя отправителя письма
	*@return void
	*/	
	public function setFromName($from_name)
	{
		$this->from_name = $from_name;
	}
	
	/**
	*Устонавливает свойство $type. 
	*@param string $type  Тип отпраляемого письма
	*@return void
	*/	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	/**
	*Устонавливает свойство $encoding. 
	*@param string $encoding  Кодировка отпраляемого письма
	*@return void
	*/	
	public function setEncoding($encoding)
	{
		$this->encoding = $encoding;
	}
	
	/**
	*Метод отвечающий за отпрвку письма. 
	*@param string $to  Кому отправлять письмо
	*@param array $data  Массив данных для подстановки в шаблон
	*@param string $template  Имя шаблона письма для подстановки переданных значений
	*@return bool
	*/
	public function send($to, $data, $template)
	{
		$from = "?=utf-8?B?".base64_encode($this->from_name)."=? <".$this->from.">";
		$header = "From: ".$from."\r\nReply-To: ".$from."\r\nContent-type: ".$this->type."; charset=\"".$this->encoding."\"\r\n";
		$text = $this->view->render($template, $data, true);
		$lines = preg_split("/(\\r\\n)|\\n/", $text);
		$subject = "?=utf-8?B?".base64_encode($lines[0])."=?";
		$body = "";
		for($i = 1; $i < count($lines); $i++)
		{
			$body.= $lines[$i];
			if($i != (count($lines) - 1)) $body."\n";
		}
		if($this->type = "text/html") $body = nl2br($body);
		return mail($to, $subject, $body, $header);
	}
}
?>