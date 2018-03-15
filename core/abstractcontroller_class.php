<?php
/**
*Базовый контроллер уравня ядра. Класс Ядра шаблон будующих контроллеров. 
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class AbstractController
{
	
	/**
	*@var Object $view  Обьект шаблонизатора
	*/
	protected $view;
	
	/**
	*@var Object $request  Обьект обработки POST и GET запросов
	*/
	protected $request;
	
	/**
	*@var Object $fp  Обьект процессора форм
	*/
	protected $fp = null;
	
	/**
	*@var Object $auth_user  Обьект вторизированого пользователя
	*/
	protected $auth_user = null;
	
	/**
	*@var Object $jsv  Обьект JavaScript Валидатора(Валидатор форм)
	*/
	protected $jsv = null;
	
	/**
	*Конструктор класса. Инициализирует свойства класса. Если небыла запущенна ранее, то запускаеться сессия
	*Если метод access() вернет false (проверка доступа у пользователя) выполнется метод  accessDenied() и создастся исключение "ACCESS_DENIED"(обробатывается в обьекте клсса Route() )
	*@param object $view Обьект шаблонизатора
	*@param object $message Обьект сист. сообщений
	*@return void
	*/	
	public function __construct($view, $message)
	{
		if(!session_id()) session_start();
		$this->view = $view;
		$this->request = new Request();
		$this->fp = new FormProcessor($this->request, $message);
		$this->jsv = new JSValidator($message);
		$this->auth_user = $this->authUser();
		if(!$this->access())
		{
			$this->accessDenied();
			throw new Exception("ACCESS_DENIED");
		}
	}

	/**
	*Абстрактный метод. Принемает строку и отрисовывает конечную страницу 
	*
	*@param string $str
	*@return void
	*/
	abstract protected function render($str);
	
	/**
	*Абстрактный метод. События при запрещенном доступе 
	*
	*@param string $str
	*@return void
	*/	
	abstract protected function accessDenied();
	
	/**
	*События при 404 ошибке
	*
	*@todo метод назвался access404!
	*@return void
	*/
	abstract protected function action404();
	
	/**
	*Проверяет авторезирован ли пользователь или нет 
	*
	*@return mixed(null|Object)
	*/
	protected function authUser()
	{
		return null;
	}
	
	/**
	*Проверяет разрешен ли доступ к контенту страницы
	*
	*@return bool
	*/
	protected function access()
	{
		return true;
	}
	
	/**
	*События если запрашиваемая страница не найдена
	*
	*@return void
	*/
	final public function notFound()
	{
		$this->action404();
	}
	
	/**
	*Перенаправление на определленый  адрес
	*
	*@param string $url URL адрес редиректа
	*@return void
	*/
	final protected function redirect($url)
	{
		header("Location: $url");
		exit();
	}
	
	/**
	*Отрисовка шаблона
	*
	*@param array $modules Массив дулей
	*@param string $layout Название файла шаблона
	*@param array $pararms Массив параметров
	*@return string
	*/
	final protected function renderData($modules, $layout, $pararms = array())
	{
		if(!is_array($modules)) return false;
		foreach($modules as $key=>$value)
		{
			$params[$key] = $value;
		}
		return $this->view->render($layout, $params);
	}
	
	
}
?>