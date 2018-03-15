<?php
abstract class BreadcrumbsModule extends Module{
	public function __construct(){
		parent::__construct();
		$this->add('breadcrumbs');
	}
}

?>