<?php
/**
*Класс Ядра для работы с URL адрессами.
*
*Клас содержет статические методы по созданию, разбору, преобразованию URL адреса. 
*Получения Action и Controller из URL адреса
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
class URL
{
	/**
	*Формируется URL на основе переданных параметров
	*
	*@param string $action - Действие для контроллера(Экшен)
	*@param string $controller - Контроллер
	*@param array $data Массив GET парамметров
	*@param string $amp Разделитель GET параметров(Амперсанд). Если true  то в URL подстовляется '&amp;'(нужен для валидности на странице), "&"(XML нотация) - если false
	*@param string $address  URL адрес Хоста(http://site.net)
	*@return getAbsolute($address, $uri)
	*/
	public static function get($action, $controller = "", $data = array(), $amp = true, $address = "")
	{
		if($amp) $amp = "&amp;";
		else $amp = "&";
		//http://site.net/user/editprofile
		if($controller) $url = "/$controller/$action";
		//http://site.net/index.html
		else $url = "/$action";
		if(count($data) !== 0)
		{
			$url.="?";
			foreach($data as $key => $value) $url.= $key."=".$value.$amp;
			$url = substr($url, 0, -strlen($amp));
		}
		return self::getAbsolute($address, $url);
	}
	/**
	*Формируется URL на основе URL адрес Хоста(http://site.net)  и сформированой строки GET парамметров
	*
	*@param string $address  URL адрес Хоста(http://site.net)
	*@param string $url - Строка GET параметров
	*@return string
	*/
	public static function getAbsolute($address, $url)
	{
		return $address.$url;
	}
	
	/**
	*Получения текущего URL адреса
	*
	*@param string $address  URL адрес Хоста(http://site.net)
	*@param string $amp Разделитель GET параметров(Амперсанд). Если true  то в URL подстовляется '&amp;'(нужен для валидности на странице), "&"(XML нотация) - если false
	*@return string
	*/
	public static function setAbsolute($address = "", $amp = false)
	{
		$url = self::getAbsolute($address, $_SERVER['REQUEST_URI']);// $_SERVER['REQUEST_URI'] =>'/index.html'
		if($amp) $url = str_replace("&", "&amp;", $url);
		return $url;
	}


	/**
	*Получения Контроллера и Экшена. Разбор URL адреса
	*
	@return array
	*/
	public static function getControllerAndAction()
	{
		$url = self::setAbsolute(Config::ADDRESS);
		$url = parse_url($url);
		$controller_name = "Main";
		$action_name = "index";
		if(!empty($url['path']) && $url['path'] !== "/")
		{
			$route = explode("/", $url['path']);
			if(!empty($route[2]))
			{
				if(!empty($route[1])) $controller_name = $route[1];
				$action_name = $route[2];
			}else $action_name = $route[1];
		}
		return array($controller_name, $action_name);
	}
	
	/**
	*Удоляет GET параметр 'page' из URL
	*
	*@param string $url URL запрос
	*@param string $amp Амперсанд(GET разделитель)
	*
	@return string
	*/
	public static function deletePage($url, $amp = true)
	{
		$url = self::deleteGet($url, 'page', $amp);
		return $url;
	}
		
	/**
	*Добаваляет GET парамметр 'page' в URL(page=). Используется в темплетах
	*
	*@param string $url URL запрос
	*@param string $amp Амперсанд(GET разделитель)
	*
	@return string
	*/
	public static function addTemplatePage($url, $amp = true)
	{
		$url = self::addGet($url, 'page=', $amp);
		return $url;
		
	}
	
	/**
	*Добаваляет GET парамметр в URL.
	*
	*@param string $url URL запрос
	*@param mixed $get GET парамметр. может быть строкой "Ключ=Значение" или массивом строк. 
	*@param string $amp Амперсанд(GET разделитель)
	*
	@return string
	*/
	public static function addGet($url, $get, $amp)
	{
		if($amp) $amp = "&amp;";
		else $amp = "&";
		if(strpos($url, '?') !== false) $url.= $amp;
		else $url.= "?";
		if(is_array($get))
		{
			$url.= implode($amp, $get);
		}
		else $url.= $get;
		
		return $url;
	}
	
		
	/**
	*Удоляет GET парамметр из URL.
	*
	*@param string $url URL запрос
	*@param string $get Имя GET парамметр каторый надо удалить. 
	*@param string $amp Амперсанд(GET разделитель)
	*
	@return string
	*/
	public static function deleteGet($url, $get, $amp)
	{
		if(strpos($url, '?') !== false)
		{
			$url = str_replace("&amp;", "&", $url);
			list($str_adr, $str_qs) = array_pad(explode('?', $url), 2, "");
			if(!empty($str_qs))
			{
				parse_str($str_qs, $arr_get);
				unset($arr_get[$get]);//unset($arr_get['name']);
				if(count($arr_get) > 0)
				{
					//var_dump(is_numeric(array_keys($arr_get)[0]));
					
					if(is_numeric(array_keys($arr_get)[0]))$arr_get= array_values($arr_get);
					$url  = $str_adr."?".http_build_query($arr_get);
					if($amp) $url = str_replace("&", "&amp;", $url);
				}
				else $url = $str_adr;
			}
		}
		return $url;
	}
	/**
	*Добавление якоря к URL. Если будет указан якорь в виде идентификатора обекта DOM, то переход по URL спазиционирует на этом обьекте
	*
	*@param string $url URL адрес
	*@param string $id Идентификатор обекта DOM. 
	*
	*@return string
	*/
	public static function addID($url, $id) {
		return $url."#".$id;
	}
	
	/**
	*Удаляет все GET парамметры из URI.
	*
	*@param string $url URi запрос
	*
	@return string
	*/
	public static function deleteAllGet($uri){
		$pos = strpos($uri, '?');
		if($pos){
			return substr($uri, 0, $pos);
		}
		return $uri;
	}
}
?>