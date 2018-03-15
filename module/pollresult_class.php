<?php
/**
*Клас реализующий модуль выдачи результатов опроса
*
*@TODO Проверить работоспобность модуля. Непонятно откуда бирутся свойства $d->voters, $d->parcent; Работают ли методы-перехватчики SET, GET.
*@author Антон Манузин
*@package module
*@version v1.0
*/

class PollResult extends BreadcrumbsModule{

	public function __construct(){
		parent::__construct();
		$this->add("title");
		$this->add("message");
		$this->add("data", null, true);
	}
/**
* Метод расчитывает общее число голосо и процент голосов по каждому опросу перед подстановкой в шаблон
*
*@return void
*/
	public function preRender(){
		$this->add("countVoters");// Общее количество голосов
		$countVoters = 0;
		foreach($this->data as $d) $countVoters+= $d->voters; 
		foreach($this->data as $d){
			if($d->voters != 0)$d->percent = round($d->voters / $countVoters * 100);
			else $d->percent  =0;
		};
		$this->countVoters = $countVoters;
	}
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля PollResult(выдача результатов опроса)
*
*@return string
*/	
	public function getTmplFile(){
		return "pollresult";
	}
}
?>