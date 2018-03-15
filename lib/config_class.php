<?php
/**
*Вспомогательный класс с конфигурационными константами
*
*@author Антон Манузин
*@package lib
*@version v1.0
*/
 abstract class Config
 {
	/**
	*имя сайта
	*/
	const SITENAME = "fantom.tekknow.com.ua";
	
	/**
	*секретное слово для хеша
	*/
	const SECRET = "s3fAm24SFAuj5b5f";
	
	/**
	*адрес сайта 
	*/	
	const ADDRESS = "HTTP://fantom.tekknow.com.ua";

	/**
	* Имя админист ратора сайта
	*/
	const ADM_NAME = "Аминистратор";

	/**
	*эл. почта администратора сайта
	*/
	const ADM_EMAIL = "admin@tekknow.com.ua"; 
	
	/**
	*имя хоста для подключения к БД
	*/
	const DB_HOST = "localhost"; 

	/**
	*имя пользователя для подключения к БД
	*/
	const DB_USER = "root";

	/**
	*пароль для подключения к БД
	*/
	const DB_PASSWORD = "";

	/**
	*имя подключаемой БД
	*/
	const DB_NAME = "cms";

	/**
	*префикс подклячаемой БД
	*/
	const DB_PREFIX = "xyz_";
	
	/**
	*символ для подстановки(замены) параметра в SQL запросе
	*/
	const DB_SYM_QUERY = "?";

	/**
	*формат представления даты
	*/
	const FORMAT_DATE = "%d.%m.%Y %H:%M:%S";
	
	/**
	*путь к картинкам
	*/
	const DIR_IMG = "/images/";

	/**
	*путь к картинкам новостей
	*/
	const DIR_IMG_ARTICLES = "/images/articles/";

	/**
	*путь к картинкам аватарок
	*/
	const DIR_IMG_AVATAR = "/images/avatar/";
	
	/**
	*путь к локальной директории шаблонов
	*/
	const DIR_TMPL = "C://wamp64/www/site/tmpl/";
	
	/**
	*Название файла базового шаблона 
	*/	
	const LAYOUT = 'main'; 
	
	/**
	*путь к шаблонам эл. писем
	*/	
	const DIR_EMAILS = "C://wamp64/www/site/tmpl/emails";

	/**
	*путь к файлу сист. сообщений
	*/		
	const FILE_MESSAGES = "C://wamp64/www/site/text/messages.ini"; 

	/**
	*количество отображаемых новостей на странице
	*/		
	const COUNT_ARTICLES_ON_PAGE = 2;

	/**
	*количество строниц для переключения
	*/	
	const COUNT_SHOW_PAGES = 10; 
	
	/**
	*минимальное количество символов для запроса поиска
	*/	
	const MIN_SEARCH_LEN = 3; 

	/**
	*количество символов текста отображения результата поиска
	*/	
	const LEN_SEARCH_RES = 255;
	
	/**
	*путь к картинки с дефоултным аватаром
	*/	
	const DEFAULT_AVATAR = "default.png";

	/**
	*максимальный обьем файла для загрузки аватара
	*/	
	const MAX_SIZE_AVATAR = 51200;

 }
?>