<?php
class Intro extends BreadcrumbsModule{
	public function __construct(){
		parent::__construct();
		$this->add('obj');
	}
	
	public function getTmplFile(){
		return 'intro';
	}
}
?>