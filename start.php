<?php
	mb_internal_encoding('UTF-8');//Кодировка при выполнении скрипта для строковых функций
	error_reporting(E_ALL);//Отчет по всем ошибкам
	ini_set('display', 1);//Изменение настройка конфигурационного файла php.ini на момент выполнения скрипта(display = 1, тоесть вывод всех ошибок)

	set_include_path(get_include_path().PATH_SEPARATOR.'lib'.PATH_SEPARATOR.'core'.PATH_SEPARATOR.'object'.PATH_SEPARATOR.'validator'.PATH_SEPARATOR.'controllers'.PATH_SEPARATOR.'module');
	spl_autoload_extensions('_class.php');//Задает расширение для автолоуда
	spl_autoload_register();//Запуск автолоуда со стандартными настройками
	
	/*Обьявление констант для меню*/
	define('MAINMENU', 1);
	define('TOPMENU', 2);
	define('KB_B', 1024);//Сколько байт в килобайте
	define('PAY_COURSE', 1); //Какойто платный курс, скорее всего идентификатор
	define('FREE_COURSE', 2);
	define('ONLINE_COURSE', 3);
	/*
	Создается обьект бызы данных и записывается в свойство клвсв AbstractObjectDB,
	для того чтобы все наследниги базавого класа(класы для созданиея обектов для реализации модулей)
	могди использовать этот обьект для получения соотвествующих значений БД  и монипуляции ими. 
	*/
	AbstractObjectDB::setDB(DataBase::getDBO());
?>