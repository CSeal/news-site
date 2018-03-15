<?php
	/**
	*Маршрутизатор. Класс Ядра отсвечающий за получение Контроллера и Действие. И их последующй вызов. 
	*
	*В случае если запрвшиваемого Действия не существует возбуждается исключение. Если сообщение исключения не равно ACCESS_DENIED(Доступ запрещен) то выполняется действие контроллера action404() 
	*
	*@author Антон Манузин
	*@package core
	*@version v1.0
	*/
class Route
{
	/**
	*получение Контроллера и Действие. И их последующй вызов. 
	*
	*В случае если запрвшиваемого Действия не существует возбуждается исключение. Если сообщение исключения не равно ACCESS_DENIED(Доступ запрещен) то выполняется действие контроллера action404() 
	*Исключение контроллера  реализуется в классе AbstractController и его дочерних классах
	*@return void
	*/
	public static function start()
	{
		$caName = URL::getControllerAndAction();
		$controllerName = $caName[0]."Controller";
		$actionName = "action".$caName[1];
		try
		{
			if(class_exists($controllerName)) $controller = new $controllerName();
			if(method_exists($controller, $actionName)) $controller->$actionName();
			else throw new Exception();
		}
		catch(Exception $e)
		{
			if($e->getMessage() != "ACCESS_DENIED") $controller->notFound();
		}
	}
}
?>