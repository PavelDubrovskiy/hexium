<?
class Funcs {

    public static $uri = array(); //uri в виде массива
    public static $pr = '';
    public static $sites = array();
    public static $sitesMenu = array();
    public static $lang=DEFLANG;
	public static $langs=array('rus','eng','ger');
	public static $langsNames=array('rus'=>'Рус','eng'=>'Eng','ger'=>'Deu');
    public static $site = 'www';
    public static $siteDB = 1;
    public static $parent = 1;
    public static $path = '';
    public static $vpr = '';
    public static $cdir = '';
    public static $conf = array();
    public static $reference = array();
    public static $referenceId = array();
    public static $infoblock = array();
    public static $infoblockNum = array();
    public static $prop = array();
    public static $error = '';
    public static $mobile = false;
	public static $weekRus = array('Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс');
	public static $monthsRusSm = array('Янв', "Фев", "Март", "Апр", "Май", "Июнь", "Июль", "Авг", "Сен", "Окт", "Ноя", "Дек");
    public static $monthsEng = array('january', 'fabruary', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
    public static $monthsEngB = array('January', 'Fabruary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    public static $monthsRus = array('января', "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
    public static $monthsRusB = array('Январь', "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
    public static $monthsInt = array('Январь' => 1, "Февраль" => 2, "Март" => 3, "Апрель" => 4, "Май" => 5, "Июнь" => 6, "Июль" => 7, "Август" => 8, "Сентябрь" => 9, "Октябрь" => 10, "Ноябрь" => 11, "Декабрь" => 12);
    public static $monthsStr = array('января' => '01', "февраля" => '02', "марта" => '03', "апреля" => '04', "мая" => '05', "июня" => '06', "июля" => '07', "августа" => '08', "сентября" => '09', "октября" => '10', "ноября" => '11', "декабря" => '12');
    public static $monthsDays = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);


    function __construct() {
		Funcs::$langs=unserialize(DEFLANGS);
        //Заполняем uri
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false)
            $uri = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?'));
        else
            $uri = $_SERVER['REQUEST_URI'];
        self::$uri = explode('/', substr(str_replace('.html', '', $uri), 1, strlen($uri)));

        if (self::$uri[count(self::$uri) - 1] == '')
            unset(self::$uri[count(self::$uri) - 1]);
        if(isset(Funcs::$uri[0]) && Funcs::$uri[0] == 'api') {
            unset(Funcs::$uri[1]);
            $uri = Funcs::$uri;
            Funcs::$uri = array();
            foreach ($uri as $item)
                Funcs::$uri[] = $item;
        }
    }
    public static function getFileInfo($file) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $file)) {
            $temp = array();
            $raz = explode('.', $file);
            $temp['raz'] = $raz[count($raz) - 1];
            $temp['filesize'] = filesize($_SERVER['DOCUMENT_ROOT'] . $file);
            $temp['absolutesize'] = $temp['filesize'];
            if ($temp['filesize'] / 1024 < 1)
                $temp['filesize'] = $temp['filesize'] . ' Б';
            elseif ($temp['filesize'] / 1024 / 1024 < 1)
                $temp['filesize'] = round($temp['filesize'] / 1024, 2) . ' КБ';
            else
                $temp['filesize'] = round($temp['filesize'] / 1024 / 1024, 2) . ' МБ';
            $temp['raz'] = strtolower($temp['raz']);
            $temp['filesize'] = strtoupper($temp['filesize']);
            $name = explode('/', $file);
            $temp['name'] = $name[count($name) - 1];
            $temp['path'] = $file;
            if (in_array(strtolower($temp['raz']), array('jpeg', 'jpg', 'png', 'gif', 'bmp'))) {
                $temp['type'] = 'image';
            } elseif (in_array(strtolower($temp['raz']), array('wav', 'mp3'))) {
                $temp['type'] = 'audio';
            } elseif (in_array(strtolower($temp['raz']), array('mov', 'flv', 'avi', 'mpeg', 'mkv'))) {
                $temp['type'] = 'video';
            } else {
                $temp['type'] = 'application';
            }
            $temp['mime'] = $temp['type'];
            return $temp;
        } else {
            return array();
        }
    }
    public static function Transliterate($string) {
        $cyr = array(
            "Щ", "Ш", "Ч", "Ц", "Ю", "Я", "Ж", "А", "Б", "В",
            "Г", "Д", "Е", "Ё", "З", "И", "Й", "К", "Л", "М", "Н",
            "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ь", "Ы", "Ъ",
            "Э", "Є", "Ї", "І",
            "щ", "ш", "ч", "ц", "ю", "я", "ж", "а", "б", "в",
            "г", "д", "е", "ё", "з", "и", "й", "к", "л", "м", "н",
            "о", "п", "р", "с", "т", "у", "ф", "х", "ь", "ы", "ъ",
            "э", "є", "ї", "і"
        );
        $lat = array(
            "Shch", "Sh", "Ch", "C", "Yu", "Ya", "J", "A", "B", "V",
            "G", "D", "e", "e", "Z", "I", "y", "K", "L", "M", "N",
            "O", "P", "R", "S", "T", "U", "F", "H", "",
            "Y", "", "E", "E", "Yi", "I",
            "shch", "sh", "ch", "c", "Yu", "Ya", "j", "a", "b", "v",
            "g", "d", "e", "e", "z", "i", "y", "k", "l", "m", "n",
            "o", "p", "r", "s", "t", "u", "f", "h",
            "", "y", "", "e", "e", "yi", "i"
        );
        for ($i = 0; $i < count($cyr); $i++) {
            $c_cyr = $cyr[$i];
            $c_lat = $lat[$i];
            $string = str_replace($c_cyr, $c_lat, $string);
        }
        $string = preg_replace(
                "/([qwrtpsdfgjhklzxcvbnmQWRTPSDFGJHKLZXCVBNM]+)e/", "\${1}e", $string);
        $string = preg_replace(
                "/([qwrtpsdfgjhklzxcvbnmQWRTPSDFGJHKLZXCVBNM]+)/", "\${1}'", $string);
        $string = preg_replace("/([eyuioaEYUIOA]+)[Kk]h/", "\${1}h", $string);
        $string = preg_replace("/^kh/", "h", $string);
        $string = preg_replace("/^Kh/", "H", $string);
        $string = preg_replace("/[\'\"\?]+/", "", $string);
        $string = str_replace(" ", "-", $string);
        $string = preg_replace("/[^0-9a-z_-]+/i", "", $string);

        return strtolower($string);
    }

    function encodestring($string) {
        $string = str_replace(array(" ", "\"", "&", "<", ">"), array(" "), $string);
        $string = preg_replace("/[_\s\.,?!\[\](){}]+/", "_", $string);
        $string = preg_replace("/-{2,}/", "--", $string);
        $string = preg_replace("/_-+_/", "--", $string);
        $string = preg_replace("/[_\-]+$/", "", $string);
        $string = $this->Transliterate($string);
        $string = strtolower($string);
        $string = preg_replace("/j{2,}/", "j", $string);
        $string = preg_replace("/[^0-9a-z_\-]+/", "", $string);
        return $string;
    }

    public static function getFG($free, $amp = '', $first = false) {
        $data = array();
        foreach ($_GET as $key => $item) {
            if (!is_array($free) && $key != $free) {
                if (is_array($item)) {
                    foreach ($item as $arr) {
                        $data[] = $key . '[]=' . $arr;
                    }
                } else {
                    $data[] = $key . '=' . $item;
                }
            }
            if (is_array($free) && !in_array($key, $free)) {
                if (is_array($item)) {
                    foreach ($item as $arr) {
                        $data[] = $key . '[]=' . $arr;
                    }
                } else {
                    $data[] = $key . '=' . $item;
                }
            }
        }
        $text = implode('&', $data);
        if ($text != '') {
            if ($first == true) {
                return $amp . $text . '&';
            } else {
                return $amp . $text;
            }
        } elseif ($amp == '?' && $first == false) {
            return '/' . implode('/', Funcs::$uri) . '/';
        } else {
            if ($first == true) {
                return '?';
            } else {
                return '';
            }
        }
    }
    public static function convertDate($date) {
        return date('d', strtotime($date)) . ' ' . Funcs::$monthsRus[date('n', strtotime($date)) - 1] . ' ' . date('Y', strtotime($date)) . ' года';
    }
    public static function resizePicCrop($input, $output, $width, $height, $raz) {
        /* if (file_exists ( $output ))
          unlink ( $output ); */
        $size = getimagesize($input);
        $w = $size [0];
        $h = $size [1];
        if ($height == '')
            $height = $h;
        if ($width == '')
            $width = $w;
        if ($w > $h) {
            if ($h < $height) {
                $a = 1;
                $newy = $h;
            } else {
                $a = $height / $h;
                $newy = $height;
            }
            $newx = $a * $w;
        } else {
            if ($w < $width) {
                $a = 1;
                $newx = $w;
            } else {
                $a = $width / $w;
                $newx = $width;
            }
            $newy = $a * $h;
        }
        if ($newx < $width) {
            if ($w < $width) {
                $a = 1;
                $newx = $w;
            } else {
                $a = $width / $w;
                $newx = $width;
            }
            $newy = $a * $h;
        }
        if ($newy < $height) {
            if ($h < $height) {
                $a = 1;
                $newy = $h;
            } else {
                $a = $height / $h;
                $newy = $height;
            }
            $newx = $a * $w;
        }
        $source = imagecreatefromstring(file_get_contents($input)) or die('Cannot load original Image');
        $target = imagecreatetruecolor($newx, $newy);
        imagealphablending($target, true);
        imagecopyresampled($target, $source, 0, 0, 0, 0, $newx, $newy, $size [0], $size [1]);

        if ($newx < $width)
            $width = $newx;
        if ($newy < $height)
            $height = $newy;
        $left = 0;
        $top = 0;
        if (($newx - $width) > 0)
            $left = ($newx - $width) / 2;
        if (($newy - $height) > 0)
            $top = ($newy - $height) / 2;

        //$source = imagecreatefromstring(file_get_contents($input)) or die ( 'Cannot load original Image' );
        $target2 = imagecreatetruecolor($width, $height);
        imagealphablending($target2, true);
        imagecopy($target2, $target, 0, 0, $left, $top, $width, $height) or die('Cannot copy');

        if ($raz == "png") {
            imagepng($target2, $output);
        } elseif ($raz == "gif") {
            imagegif($target2, $output);
        } elseif ($raz == "bmp") {
            imagebmp($target2, $output);
        } else {
            imagejpeg($target2, $output);
        }
        imagedestroy($target);
        imagedestroy($target2);
        imagedestroy($source);
    }
    public static function MakeTime($time) {
        return mktime(date('H', strtotime($time)), date('i', strtotime($time)), 0, date('n', strtotime($time)), date('d', strtotime($time)), date('Y', strtotime($time)));
    }
    public static function generate_password($number) {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0');
        // Генерируем пароль  
        $pass = "";
        for ($i = 0; $i < $number; $i++) {
            // Вычисляем случайный индекс массива  
            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }
        return $pass;
    }

    public static function chti($string, $ch1, $ch2, $ch3) {
        if (!is_numeric($string))
            $string = '0';
        $ff = Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        if (substr($string, -2, 1) == 1 AND strlen($string) > 1)
            $ry = array("0 $ch3", "1 $ch3", "2 $ch3", "3 $ch3", "4 $ch3", "5 $ch3", "6 $ch3", "7 $ch3", "8 $ch3", "9 $ch3");
        else
            $ry = array("0 $ch3", "1 $ch1", "2 $ch2", "3 $ch2", "4 $ch2", "5 $ch3", "6 $ch3", "7 $ch3", "8 $ch3", "9 $ch3");
        $string1 = substr($string, 0, -1) . str_replace($ff, $ry, substr($string, -1, 1));
        return $string1;
    }
	public static function dfe($num){
		$data='';
		if(floor($num/26)==0){
			$data.=chr($num+65);
		}elseif(floor($num/26/26)==0){
			$data.=chr(floor($num/26-1)%26+65).chr($num%26+65);
		}elseif(floor($num/26/26/26)==0){
			$data.=chr(floor($num/26/26-1)%26+65).chr(floor($num/26-1)%26+65).chr($num%26+65);
		}
		return $data;
	}
}
?>