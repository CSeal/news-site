<?php
class MainController extends Controller{
	/**
	*Вывод главной страницы
	*
	*
	*return
	*/
	const HEAD_TITLE = "Тренировачный сайт";
	public function actionindex(){
		$this->title = self::HEAD_TITLE;
		$this->meta_desc = "Мой первый сайт с самописным MVC движком";
		$this->meta_key = "тестовый сайт, MVC, MVC движок";
		$articles = ArticleDB::getAllShow(Config::COUNT_ARTICLES_ON_PAGE, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE), true);
		$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");
		$blog = new Blog();
		$blog->articles = $articles;
		$blog->pagination = $pagination;
		$this->render($this->renderData(array("blog" => $blog), "index"));
	}
	
	public function actionsection(){
		$sectionDB = new SectionDB();
		$sectionDB->load($this->request->id);
		if(!$sectionDB->isSaved()){
			$this->notFound();
		};
		$this->section_id = $sectionDB->id; 
		$this->title = self::HEAD_TITLE.' | '.$sectionDB->title;
		$this->meta_desc = $sectionDB->meta_desc;
		$this->meta_key = $sectionDB->meta_key;
		
		$breadcrumbs = $this->getBreadCrumbs();
		$breadcrumbs->addDataItem($sectionDB->title);
		
		$intro = new Intro();
		$intro->breadcrumbs = $breadcrumbs;
		$intro->obj = $sectionDB;

		$blog = new Blog();
		$articles = ArticleDB::getAllOnPageAndSectionId($this->section_id , Config::COUNT_ARTICLES_ON_PAGE, false);
		$moreArticles = ArticleDB::getAllOnSectionID($this->section_id, true);
		$i = 1;
		foreach($moreArticles as $key=>$article){
			if($i > Config::COUNT_ARTICLES_ON_PAGE) break;
			unset($moreArticles[$key]);
			++$i ;
		};
		unset($i);
		$blog->articles = $articles;
		$blog->moreArticles = $moreArticles;
		$this->render($intro.$blog);
	}
	
	public function actioncategory(){
		$categoryDB = new CategoryDB();
		$categoryDB->load($this->request->id);
		if(!$categoryDB->isSaved()){
			$this->notFound();
		}
		$this->category_id = $categoryDB->id;
		$this->title = self::HEAD_TITLE.' | '.$categoryDB->title;
		$this->section_id = $categoryDB->section_id;
		$this->meta_desc = $categoryDB->meta_desc;
		$this->meta_key = $categoryDB->meta_key;
		
		$sectionDB = new SectionDB();
		$sectionDB->load($categoryDB->section_id);
		$breadcrumbs = $this->getBreadCrumbs();
		$breadcrumbs->addDataItem($sectionDB->title, $sectionDB->link);
		$breadcrumbs->addDataItem($categoryDB->title);
		
		$intro = new Intro();
		$intro->breadcrumbs = $breadcrumbs;
		$intro->obj = $categoryDB;
		
		$blog = new Blog();
		$articles = ArticleDB::getOnCatIDWithPostHandling($this->category_id, Config::COUNT_ARTICLES_ON_PAGE, false, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE));
		
		$pagination = $this->getPagination(ArticleDB::getCountOnField(ArticleDB::getTableName(), 'cat_id', $this->category_id), Config::COUNT_ARTICLES_ON_PAGE, $_SERVER['REQUEST_URI']);
		$blog->articles = $articles;
		$blog->pagination = $pagination;
		$this->render($intro.$blog);
	}
	
	public function actionarticle(){
		$sectionDB = $categoryDB = "";
		$articleDB = new ArticleDB();
		$articleDB->load($this->request->id);
		if(!$articleDB->isSaved()){
			$this->notFound();
		}
		$this->title = self::HEAD_TITLE.' | '.$articleDB->title;
		$this->meta_desc = $articleDB->meta_desc;
		$this->meta_key = $articleDB->meta_key;
		$breadcrumbs = $this->getBreadCrumbs();
		if($articleDB->section_id){
			$this->section_id = $articleDB->section_id;
			$sectionDB = new SectionDB();
			$sectionDB->load($articleDB->section_id);
			$breadcrumbs->addDataItem($sectionDB->title, $sectionDB->link);
			$this->uri_active = URL::get('section', '', array('id' => $this->section_id));
		};
		if($articleDB->cat_id){
			$this->category_id = $articleDB->cat_id;
			$categoryDB = new CategoryDB();
			$categoryDB->load($articleDB->cat_id);
			$breadcrumbs->addDataItem($categoryDB->title, $categoryDB->link);
			$this->uri_active = URL::get('category', '', array('id' => $this->category_id));
		};
		$breadcrumbs->addDataItem($articleDB->title);
		$prevArticleDB = new ArticleDB();
		$prevArticleDB->loadPreviusArticle($articleDB);
		$nextArticleDB = new ArticleDB();
		$nextArticleDB->loadNextArticle($articleDB);
		$article = new Article();
		$article->article = $articleDB;
		$article->auth_user = $this->auth_user;
		if($prevArticleDB->isSaved())$article->prev_article = $prevArticleDB;
		if($nextArticleDB->isSaved())$article->next_article = $nextArticleDB;
		$article->link_register = URL::get('register');
		$article->breadcrumbs = $breadcrumbs;
		$article->comments = CommentDB::getAllOnArticleID($article->article->id);
		$this->render($article);
	}
	public function actionpoll(){
		$message_name = 'poll';
		/*Массив $_REQUEST, который содержити данные переменных $_GET, $_POST и $_COOKIE, содержит $_GET['id'],
		а при отправки формы дополняется заначениями элем. формы $_POST['poll_data_id'] и т.д.*/
		if($this->request->poll){
			$pollData = PollDataDB::getAllOnPollID($this->request->id);
			$pollVoterDB = new PollVoterDB();
			$alredyPoll = PollVoterDB::isAlreadyPoll(array_keys($pollData));
			$check = array(array($alredyPoll, false, 'ERROR_ALREADY_POLL')); //Если $alredyPoll = истине, тогда выдостся сообщение ERROR_ALREADY_POLL в ini файле.(Проверка на эквивалент)
			$this->fp->processor($message_name, $pollVoterDB, array('poll_data_id'), $check, 'SUCCESS_POLL');
			$this->redirect(URL::setAbsolute());//Сбрасываем значние масива request отпровляемые формой
		}
		$pollDB = new PollDB();
		$pollDB->load($this->request->id);
		if(!$pollDB->isSaved()) $this->notFound();
		$this->title = 'Результат голосования: '.$pollDB->title;
		$this->meta_desc = 'Результат голосования: '.$pollDB->title.'.';
		$this->meta_key = 'результат голосования, '.mb_strtolower($pollDB->title);
		$breadcrumbs = $this->getBreadCrumbs();
		$breadcrumbs->addDataItem('Результат голосования: '.$pollDB->title);
		$poll = new PollResult();
		$poll->title = 'Результат голосования: '.$pollDB->title;
		$poll->message = $this->fp->getSessionMessage($message_name);
		$poll->data = PollDataDB::getAllDataOnPollID($pollDB->id);
		$poll->breadcrumbs = $breadcrumbs;
		$this->render($poll);
		
	}
	
	public function actionregister(){
		$message_name = 'register';
		if($this->request->registerUser){
			var_dump($this->request);
		};
		$form = new Form();
		$this->title = self::HEAD_TITLE.' | Регистрация пользователя';
		$this->meta_desc = 'Регистрация пользователя на сайт '.config::SITENAME.'.';
		$this->meta_key = 'Регистрация пользователя  сайт '.config::SITENAME;
		$breadcrumbs = $this->getBreadCrumbs();
		$breadcrumbs->addDataItem('Регистрация');
		$form->breadcrumbs = $breadcrumbs;
		$form->name = 'userRegister';
		$form->action = '/'.$message_name;
		$form->header = 'Регистрация пользователя';
		$form->message = $this->fp->getSessionMessage($message_name);
		$form->text('name', 'Имя пользователя', $this->request->name);
		$form->text('login', 'Логин пользователя', $this->request->login);
		$form->password('password', 'Пароль пользователя');
		$form->password('passwordConf', 'Подтверждение пароля');
		$form->text('email', 'Email пользователя', $this->request->email);
		$form->captcha('captcha', 'Введите символы с картинки');
		$form->submit('registerUser', 'Зарегестрироваться');
		$form->addJsv('name', $this->jsv->name(null, 6));
		$form->addJsv('login', $this->jsv->login(null, 6, 12));
		$form->addJsv('password', $this->jsv->password(null, 6, 12, 'passwordConf'));
		$form->addJsv('email', $this->jsv->email(null, 6, 12, true));
		$form->addJsv('captcha', $this->jsv->captcha());
		$this->render($form);
		
		
	}
} 
?>