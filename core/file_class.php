<?php
/**
*Работа с файлами. Класс Ядра работы с файлами. Набор статических методов  
*
*Загрузка(картинок) на сервер, удаление файлов с сервера. Проверка существования файлов на сервере 
*
*
*@author Антон Манузин
*@package core
*@version v1.0
*/
 class File
 {
	/**
	*Метод Загрузка картинок на сервер. возращает имя загруженного файла на сервере
	*
	*@param array $file Супер глобальный массив $_FILE
	*@param int $max_size Максимальный размер файла
	*@param string $dir Каталог для сахранения файла на сервере
	*@param bool $root Является ли $dir корневым каталогом
	*@param bool $source_name Файл будет сохранен на сервер под своим локальным именем или будет сгенерировано уникальное
	*@return string
	*/
	 public static function uploadIMG($file, $max_size, $dir, $root = false, $source_name = false)
	 {
		 $blackList = array(".php", ".php4", ".phtml", ".html", ".htm", ".js");
		 foreach($blackList as $item)
		 {
			 if(preg_match("/$item$/i", $file["name"])) throw new Exception("ERROR_UPLOAD_TYPE");
		 }
		 $type = $file['type'];
		 $size = $file['size'];
		 if(($type != "image/jpeg") && ($type != "image/jpg") && ($type != "image/bmp") && ($type != "image/png")) throw new Exception("ERROR_UPLOAD_IMAGE_TYPE");
		 if($size > $max_size) throw new Exception("ERROR_UPLOAD_IMAGE_SIZE");
		 if($source_name) $image_name = $file['name'];
		 else $image_name = self::getName().".".substr($type, strlen("image/"));
		 $upload = $dir.$image_name;
		 if(!$root) $upload_name = $SERVER['DOCUMENT_ROOT'].$upload_name;
		 if(!move_upload_file($file['tmp_name'], $upload_name)) throw new Exception("ERROR_UPLOAD_IMAGE");
		 return $image_name;
	 }
	 
	/**
	*Метод генерирует случайное уникальное имя
	*
	*@return string
	*/
	 public static function getName()
	 {
		 return uniqid();
	 }
	 
	/**
	*Метод удаляет файл с сервера
	*
	*@param string $file_name Имя файла для удаления
	*@param bool $root Является ли $file_name корневым каталогом
	*@return bool
	*/
	 public static function deleteFile($file_name, $root = false)
	 {
		 if(!$root) $file_name = $_SERVER['DOCUMENT_ROOT'].$file_name;
		 if(file_exists($file_name))
		 {
			unlink($file_name);
			return true;
		 }
		 return false;
	 }
	 
	/**
	*Метод проверяет присутствие файла на сервере
	*
	*@param string $file_name Имя файла для проверки
	*@param bool $root Является ли $file_name корневым каталогом
	*@return bool
	*/
	 public static function isExists($file_name, $root = false)
	 {
		 if(!$root) $file_name = $_SERVER['DOCUMENT_ROOT'].$file_name;
		 if(file_exists($file_name))return true;
		 return false;
	 }
 }
?>