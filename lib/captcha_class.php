<?php
class Captcha{
/**
*Класс для формирования,вывода и обрабодки данных каптчи
*@author Антон Манузин
*@package ./lib/
*@version v1.0
*/

/**
* Ширина окна для вывода каптчи(в пикселях)
*/
const WIDTH = 100;

/**
* Высота окна для вывода каптчи(в пикселях)
*/
const HEIGHT = 70;

/**
* Рзмер шрифта для вывода каптчи(Начальный размер)
*/
const FONT_SIZE = 16;

/**
* Количество основных символов выводимыч в каптчи
*/
const CHAR_AMOUNT = 4;

/**
* Количество символов для вывода на задний план в каптче
*/
const BG_CHAR_AMOUNT = 30;

/**
* Путь к шрифту
*/
const FONT_PATH = "fonts/verdana.ttf";

/**
*@var array $char Содержит массив символов для вывода в каптчу
*@access private
*/
private static $char = array("A", "B", "C", "D", "E", "F", "G", "H", "1", "3", "6", "8", "5");

/**
*@var array $color Содержит массив цифр для генерации цвета в каптче
*@access private
*/
private static $color = array(123, 134, 145, 185, 45, 33, 167, 148, 225, 201, 189, 199);

/**
	*Рисует Каптчу и выводит её в основной поток вывода.
	*
	*@access public
	*@return void
	*/

public static function generate(){
	if(!session_id()) session_start();
	/* Создаем белое поле для рисования каптчи*/
	$src = imagecreatetruecolor(self::WIDTH, self::HEIGHT); //Создание нового полноцветного изображения, возвращает идентификатор изображения, представляющий черное изображение заданного размера
	$bg = imagecolorallocate($src, 255, 255, 255); //Создание цвета для изображения, Возвращает идентификатор цвета в соответствии с заданными RGB компонентами. Создаем белый фон.
	imagefill($src, 0, 0, $bg); //Заливка. Производит заливку, начиная с заданных координат
	/*Заполняем его фоновым шумом*/
	for($i = 0; $i < self::BG_CHAR_AMOUNT; $i++){
		$color = imagecolorallocatealpha($src, rand(0, 254), rand(0, 254), rand(0, 254), 100); // alpha в диапозоне от 0(полная непрозрачность) до 127(полная прозрачность)
		$char = self::$char[rand(0, count(self::$char)- 1)];
		$size = rand(self::FONT_SIZE - 2, self::FONT_SIZE + 2);
		imagettftext($src, $size, rand(-45, 45), rand(self::WIDTH * 0.1, self::WIDTH * 0.9), rand(self::HEIGHT * 0.1, self::HEIGHT * 0.9), $color, self::FONT_PATH, $char); //Нанесение текста на изображение, используя шрифты FreeType2(Рстрирование шрифта)
	};
	for($i = 0; $i < self::CHAR_AMOUNT; $i++){
		$color = imagecolorallocatealpha($src, self::$color[rand(0, count(self::$color) - 1)], self::$color[rand(0, count(self::$color) - 1)], self::$color[rand(0, count(self::$color) - 1)], rand(20, 40));
		$char = self::$char[rand(0, count(self::$char)- 1)];
		$size = rand(self::FONT_SIZE * 2 - 2, self::FONT_SIZE * 2 + 2);
		imagettftext($src, $size, rand(-15, 15), self::WIDTH * 0.8 / self::CHAR_AMOUNT * $i, self::HEIGHT / 3 * 2, $color, self::FONT_PATH, $char);
		$_SESSION["code_captcha"].= $char;
	}
header("Content-Type: image/png"); //Заголовок, что будет выведена кортинка
header("Cache-Control: no-store, no-cache");
imagepng($src); //Отрисовка кортинки
imagedestroy($src);	//Освобождение памяти от картинки
}

/**
	*Сравнивает строку со значениями сиволов, сгенерируемых каптчей и сохранненую в сесии с ключом "code_captcha", с тем что вводит пользователь(переменная $code).  
	*Для проверки на стороне сервера.
	*
	*@param string $code Строка символов, которую вводит пользователь при выводи Каптчи
	*@access public
	*@return bool
	*/
public static function check($code){
	if(!session_id()) session_start();
	return $code === $_SESSION["code_captcha"]; 
}
 
}
?>