<?php
/**
*Стандартный конторлер. Абстракция 
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
abstract class Controller extends AbstractController{
	/**
	*@var string Заголовок страницы
	*/
	protected $title;
	/**
	*@var string Краткое описание страницы
	*/
	protected $meta_desc;
	/**
	*@var string Ключевые слова
	*/
	protected $meta_key;
	/**
	*@var object Обьект занимающийся отправкой писем
	*/
	protected $mail = null;
	/**
	*@var string Активный УРЛ(текущий адрес страницы)
	*/
	protected $uri_active;
	/**
	*@var int Идентификатор открытого раздел(секции) Нужно для вывода определенных курсов
	*/
	protected $section_id = 0;
	/**
	*@var int Идентификатор открытой категории. Нужно для вывода определенных курсов
	*/
	protected $category_id = 0;
	/**
	*@var obj Авторизированый пользователь
	*/
	protected $authUser;
	
	public function __construct(){
		parent::__construct(new View(Config::DIR_TMPL), new Message(Config::FILE_MESSAGES));
		$this->mail = new Mail();
		$this->uri_active = URL::deleteGET(URL::setAbsolute(), 'page', false);

	}
	/**
	*Метод отвечающий за вывод 404 ответа сервера(response whis header 404) "Страница не найдена"
	*
	*return void
	*/
	public function action404(){
		header('HTTP/1.1 404 not found');
		header('Status: 404 not found');
		$this->title = '404 не найдена';
		$this->meta_desc = 'Запрошеной страницы не существует';
		$this->meta_key = 'страница не найдена, страница не существует, 404';
		$pm = new PageMessage();
		$pm->header = 'Страница не найдена';
		$pm->text = 'К сожелению, запрошеная страница не существует. Проверте правельность ввода адреса';
		$this->render($pm);
	}
	/**
	*Метод отвечающий за вывод 403 ответа сервера(response whis header 404) "Доступ запрещен"
	*
	*return void
	*/
	protected function accessDenied(){
		$this->title = '403 Доступ запрещен';
		$this->meta_desc = 'Доступ к странице запрещен';
		$this->meta_key = 'доступ запрещен, доступ зпрещен страница, доступ запрещен 404';
		
		$pm = new PageMessage();
		$pm->header = 'Доступ запрещен';
		$pm->text = 'Доступ к данной странице заприщен. Обратитесь к администратору сайта';
		$this->render($pm);
	}
	/**
	*Метод отвечающий за формирование блоков страницы и передачи её шаблонизатору
	*
	*return void
	*/
	final protected function render($str){
		$params = array();
		$params['header'] = $this->getHeader();
		$params['topMenu'] = $this->getTopMenu();
		$params['auth'] = $this->getAuth();
		$params['slider'] = $this->getSlider();
		$params['left'] = $this->getleft();
		$params['right'] = $this->getRight();
		$params['center'] = $str;
		$params['linkSearch'] = URL::get('search');
		$this->view->render(Config::LAYOUT, $params, false);
	}
	/**
	*Метод отвечающий за формирование блока HEAD сайта(Создание обьекта и заполнения его свойств)
	*
	*return object
	*/
	protected function getHeader(){
		$header = new Head();
		$header->title = $this->title;
		$header->favicon = '/favicon.ico';
		$header->setMeta('Content-Type', 'text/html; charset=utf-8', true);
		$header->setMeta('description', $this->meta_desc, false);
		$header->setMeta('keywords', $this->meta_key, false);
		$header->setMeta('viewport', 'width=device-width', false);
		$header->css = array('/styles/main.css', '/styles/prettify.css');
		$header->js = array('/js/jquery-1.10.2.min.js', '/js/prettify.js', '/js/myscript.js', '/js/formValidator.js');
		return $header;
	} 
	/**
	*Метод отвечающий за формирование блока(формы) авторизации на сайте(Создание обьекта и заполнения его свойств)
	*
	*return object
	*/
	protected function getAuth(){
		if($this->authUser) return '';//Если пользователь авторизован то не выводится модуль авторизации; Что это за свойство?
		$auth = new Auth();
		$auth->message = $this->fp->getSessionMessage('auth');
		$auth->action = URL::setAbsolute('', true);
		$auth->linkRegister = URL::get('register');
		$auth->linkReset = URL::get('linkReset');
		$auth->linkRemind = URL::get('linkRemind');
		return $auth;
	}
	/**
	*Метод отвечающий за формирование блока верхнего меню(Создание обьекта и заполнения его свойств)
	*
	*return object
	*/
	protected function getTopMenu(){
		$items = MenuDB::getTopMenu();//Папка ObjectDB,  файл menudb_class.php  
		$topMenu = new TopMenu();
		$topMenu->uri = $this->uri_active;
		$topMenu->items = $items;
		return $topMenu;
	}
	/**
	*Метод отвечающий за формирование блока слайдера(Создание обьекта и заполнения его свойств)
	*
	*return object
	*/
	protected function getSlider(){
		$course = new CourseDB();
		$course->loadOnSectionId($this->section_id, PAY_COURSE);
		$slider = new Slider();
		$slider->course = $course;
		return $slider;
	}
	/**
	*Метод отвечающий за формирование левого блока. Возвращает строковое предстовление обьктов-модулей.
	*
	*return string
	*/
	protected function getLeft(){
		$mMenuDB = MenuDB::getMainMenu();
		$mainMenu = new MainMenu();
		
		$mainMenu->uri = $this->uri_active;
		$mainMenu->items = $mMenuDB;
		if($this->authUser){
			$userPanel = new UserPanel();
			$userPanel->uri = $this->url_active;
			$userPanel->user = $this->authUser;
			$userPanel->addItem(URL::get('editProfile', 'user'), 'Редактировать профиль');
			$userPanel->addItem(URL::get('logout'), 'Выход');
		}else{
			$userPanel = "";
		};

		$pollDB = new PollDB();
		$pollDB->loadRandom();
		if($pollDB->isSaved()){
			$poll = new Poll();
			$poll->action = URL::get('poll','',array('id'=>$pollDB->id));
			$poll->title = $pollDB->title;
			$poll->data = PollDataDB::getAllOnPollID($pollDB->id);	
		}else{
			$poll = '';
		}
		return $userPanel.$mainMenu.$poll;
	}
	/**
	*Метод отвечающий за формирование правого блока. Возвращает строковое предстовление обьктов-модулей.
	*
	*return string
	*/
	protected function getRight(){
		$course_DB1 = new CourseDB();
		$course_DB1->loadOnSectionId($this->section_id, FREE_COURSE);
		$course_DB2 = new CourseDB();
		$course_DB2->loadOnSectionId($this->section_id, ONLINE_COURSE);
		$courses = array($course_DB1, $course_DB2);
		$course = new Course();
		$course->auth_user = $this->authUser;
		$course->courses = $courses;
		$quoteDB = new QuoteDB;
		$quoteDB->loadRandom();
		$quote = new Quote();
		$quote->quote = $quoteDB;
		return $course.$quote;
	}
	/**
	*Метод возвращает смещение начала вывода статей для их постраничного вывода 
	*
	*return int
	*/
	final protected function getOffset($countArticleOnPage){
		return $countArticleOnPage * ($this->getPage() - 1);
	}
	/**
	*Метод возвращает номер текущей страницы
	*
	*return mixed
	*/
	final protected function getPage(){
		$page = ($this->request->page)? $this->request->page : 1;
		if($page > 0 ) return $page;
		else $this->notFound();
	}
	/**
	*Метод возвращает обьекит модуля постраничной навигации
	*
	*@param int countElement Общее количество элементов
	*@param int countElementOnPage  Количество элементов на страниц
	*@param int url Текущий URL
	*
	*return object
	*/
	final protected function getPagination($countElement, $countElementOnPage, $url = false){
		$countPages = ceil($countElement / $countElementOnPage);
		$active = $this->getPage();
		if(($active > $countPages) && ($active > 1)) $this->notFound();
		$pagination = new Pagination();
		if(!$url) $url =  URL::setAbsolute();
		$url = URL::deletePage($url);
		$pagination->url = $url;
		$pagination->urlPage = URL::addTemplatePage($url);
		$pagination->active = $active;
		$pagination->countElements = $countElement;
		$pagination->countElementsOnPage = $countElementOnPage;
		$pagination->countShowPages = Config::COUNT_SHOW_PAGES;
		return $pagination;
	}
	
	protected function getBreadCrumbs(){
		$breadCrumbs = new BreadCrumbs();
		$breadCrumbs->addDataItem('Главная', Config::ADDRESS);
		return $breadCrumbs;
		
	}
	
}
?>