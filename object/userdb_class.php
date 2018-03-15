<?php
class UserDB extends ObjectDB
{
	/**
	*@var string $table Название таблицы
	*/
	protected static $table = "users";
	
	private $new_password = null;
	/**
	*Конструктор класса. Передает в родительский конструктор имя таблицы добавляет свойства(ключи соответствуют названием столбцов таблицы) в масив properties
	*
	*@return void
	*/
	public function __construct()
	{
		parent::__construct(self::$table);
		$this->add('login', 'ValidateLogin');
		$this->add('email', 'ValidateEmail');
		$this->add('password', 'ValidatePassword');
		$this->add('name', 'ValidateName');
		$this->add('avatar', 'ValidateIMG');
		$this->add('date_reg', 'ValidateDate', self::TYPE_TIMESTAMP, $this->getDate());
		$this->add('activation', 'ValidateActivation', null, $this->getKey());
	}
	
	protected function setNewPassword($password)
	{
		$this->new_password = $password;
	}
	
	protected function getNewPassword()
	{
		return $this->new_password;
	}
	public function loadOnEmail($email)
	{
		return $this->loadOnField("email", $email);
	}
	
	public function loadOnLogin($login)
	{
		return $this->loadOnField("login", $login);
	}
	
	protected function postInit()
	{
		if(is_null($this->avatar)) $this->avatar = Config::DEFAULT_AVATAR;
		$this->avatar = Config::DIR_IMG_AVATAR.$this->avatar;
		return true;
	}
	
	protected function preValidate()
	{
		if($this->avatar == Config::DIR_IMG_AVATAR.Config::DEFAULT_AVATAR) $this->avatar = null;
		if(!is_null($this->avatar)) $this->avatar = basename($this->avatar);
		if(!is_null($this->new_password)) $this->password = $this->new_password;
		return true;
	}
	
	protected function postValidate()
	{
		if(!is_null($this->new_password)) $this->password = Config::hash($this->new_password, Config::SECRET);
	}
	
	public function login()
	{
		if($this->activation == "") return false;
		if(!session_id()) session_start();
		$_SESSION['auth_login'] = $this->login;
		$_SESSION['auth_password'] = $this->password;
	}
	
	public function logout()
	{
		if(session_id()) session_destroy();//if(!session_id()) session_start();
		unset($_SESSION['auth_login']);
		unset($_SESSION['auth_password']);
		
	}
	public static function authUser($login = false, $password = false)
	{
		if($login) $auth = true;
		else
		{
			if(!session_id()) session_start();
			if(!empty($_SESSION['auth_login']) && !empty($_SESSION['auth_password']))
			{
				$login = $_SESSION['auth_login'];
				$password = $_SESSION['auth_password'];
			}
			else return;
			$auth = false;
		}
		$user = new UserDB();
		if($auth) $password = Config::hash($password, Config::SECRET);
		
	}
	
} 
?>