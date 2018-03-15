<?php
/**
*Модуль отвечающий за вывод результатов поиска. Блок result
*
*@author Антон Манузин
*@package module
*@version v1.0
*/
class SearchResult extends Module{
	public function __construct(){
	  parent::__construct();
	  $this->add('query');//Поисковая фраза
	  $this->add('field');//Свойство обьекта в котором содержится искомая строка. Например текст ищется в основном в свойстве "text"
	  $this->add('error_len', false);//Ошибка связаная с длиной поисковай фразы. Слишком короткая.
	  $this->add('data', null, true)// массив данных для поиска
	}
	
/**
*Метод предварительной подготовки перед выводом HTML делает проверки на длину запроса, 
*дополняет элементы массива данных $this->data свойством  description(Краткий вывод статьи с выделенными найденый поисковыми словами)
*
*@return void
*/
	private function preRender(){
		$query = $this->query;
		$query = preg_replace('/\s{2,}/', ' ', $query);
		if(strlen($query) > Config::MIN_SEARCH_LEN){
			$query = mb_strtolower($query);
			forEach($this->data as $d) $d->description = $this->getDescription($d[$this->fieald], $query);
		}else $this->error_len = true;
		
	}
/**
*Метод возвращает часть текста с максимальным количеством совпаших поисковых фраз.
*Совпадения обворачиваются тегой <span>
*
*@TODO Проверить работоспасобность и быстроту работы метода
*@param $whereSearching string Строка для поиска.
*@param $query string Поисковая фраза.
*@return string
*/
	private function getDescription($whereSearching, $query){
		$query = explode(' ', $query);
		$j = 0; // Индекс поискового слова
		$wordPos = array();
		$whereSearchingLen = strlen($whereSearching);
		foreach($query as $queryWord){
			$offset = 0;
			$k = 1;//Индекс вхождения поискового слова
			$queryWordLen = strlen($queryWord);
			while(stripos($whereSearching, $queryWord, $offset)){
				$wordPos[$j][$k] = stripos($whereSearching, $queryWord, $offset);
				$offset = $wordPos[$j][$k] + $queryWordLen;
				++$j;
			};
			if( $k > 1){
				$wordPos[$j][0] = $queryWordLen;
				++$j;
			};
			
		};
		if($j > 0)){
			--$j;
			$startPos = 0;
			$countChapters = ceil($whereSearchingLen / Congig::LEN_SEARCH_RES);	
			$leftFront = 0;
			$topCountWordsInChapter = 0;
			for($i = 1, $rightFront = Config::LEN_SEARCH_RES; $i <= $countChapters && $j >= 0; i++){
				$countWordsInChapter = 0;
				$k = 1;
				$offset = 0;
				While(true){
					if($wordPos[$j][$k] >= $leftFront && $wordPos[$j][$k] <= $rightFront){
						$wordPosEnd = $wordPos[$j][$k] + $wordPos[$j][0];
						if(($offset = $rightFront - $wordPosEnd) < 0 ){
							$rightFront+= $offset * -1;
						};
						++$countWordsInChapter;
						unset($wordPos[$j][$k]);
						--$k;
					}else{
						--$j;
						$k = 1;
						if($j < 0){
							$j = count($wordPos) - 1;
							$leftFront = $rightFront;
							break;
						};
					};
					++$k;
				};
				if($countWordsInChapter > $topCountWordsInChapter){
					$topCountWordsInChapter = $countWordsInChapter;
					$startPos  = $leftFront;
				};
				$rightFront = Config::LEN_SEARCH_RES * $i + $offset;
			}
			$frontStartSymbol = $frontEndSymbol = '...';
			if($startPos === 0) $frontStartSymbol = '';
			if(($startPos + Config::LEN_SEARCH_RES) === $whereSearchingLen) $frontEndSymbol = '';
			$result = substr($whereSearching, $startPos, Config::LEN_SEARCH_RES);
			foreach($query as $queryWord){
				preg_replace('/'.$queryWord.'/i', '<span>'.$queryWord.'</span>', $result);
			};
			return $frontStartSymbol.$result.$frontEndSymbol;
		}
	}
/**
*Метод возращает имя файла шаблона отвечающего за вывод модуля SearchResult(Элемент поиска)
*
*@return string
*/	 
	public function getTmplFile(){
		return 'serchresult';
	}

}
?>