<?php

class Utils {

	public static function debug($data) {
		if(is_array($data) || is_object($data)) $data = json_encode($data);
		file_put_contents(ROOT.DS.'tmp'.DS.'console', $data.PHP_EOL);
	}

	public static function getURL($url,$method='GET',$fields=null) {
		$ch = curl_init();
		if(isset($fields)) {
			$params = '';
			foreach($fields as $key => $val) {
				$params .= "{$key}={$val}&";
			}
			rtrim($params,'&');
		}
		switch ($method) {
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, count($fields));
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			
			default:
				if(isset($params)) {
					$url .= '?'.$params;
				}
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				break;
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}

	public static function fileExists($files=array()) {
		foreach($files as $file) {
			if(file_exists($file)) {
				return $file;
			}
		}
		return false;
	}

	public static function dir($dir) {
		if(!file_exists($dir)) {
			mkdir($dir,0777,true);
		}
		return $dir;
	}

	public static function is($type) {
		return $_SERVER['REQUEST_METHOD'] === strtoupper($type);
	}
		
	public static function redirect($location,$include_params=false) {
		if($include_params) {
			unset($_GET['route']);
			$params = http_build_query($_GET);
			if(!empty($params)) $location = $location.'?'.http_build_query($_GET);
		}
		header("Location: {$location}");
		exit;
	}

	public static function forward($from,$to,$include_params=true) {
		if(strpos(self::currentURL(),$from) !== false) {
			self::redirect($to,$include_params);
		}
	}

	public static function throwError($code=500) {
		switch($code) {
			case 500:
				self::redirect('500.php');
		}
	}

	public static function sendResponseCode($code,$exit=true) {
		# Consider adding support for < 5.4
		http_response_code($code);
		if($exit) exit;
	}

	public static function exportAll($objs,$excludes=[]) {
		$result = array();
		if($objs) {
			foreach ($objs as $obj) {
				if(method_exists($obj, 'export')) array_push($result,$obj->export($excludes));
			}
		}
		return $result;
	}

	public static function flash($message=""){
		if(!empty($message)){
			return "<p class=\"message\">{$message}</p>";
		} else {
			return "";
		}
	}

	public static function fixFilesArray($files) {
		$fixed_files = [];
		foreach($files as $key=>$all) {
			foreach($all as $i=>$val) {
				$fixed_files[$i][$key] = $val;
			}
		}
		return $fixed_files;
	}

	public static function fputcsvEOL($path, $array, $delimiter=',', $enclosure='"',$eol="\n") {
		$return = fputcsv($path, $array, $delimiter, $enclosure);
		if($return !== FALSE && $eol != "\n" && fseek($path, -1, SEEK_CUR)) {
			fwrite($path, $eol);
		}
		return $return;
	}

	public static function tagged($content){
		$content = preg_replace("~(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?~", "<a target=\"_blank\" href=\"\\0\">\\0</a>", $content);
		$content = preg_replace("~[_A-Za-z0-9-]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*(\.[A-Za-z]{2,3})~", "<a href=\"mailto:\\0\">\\0</a>", $content);
		return $content;
	}

	public static function toId($str){
		return preg_replace("/[^a-zA-Z0-9s-]/","_",$str);
	}

	public static function plurify($singular) {
		if(substr($singular, -1) == 'y') {
			$plural = rtrim($singular, 'y') . 'ies';
		} else {
			$plural = $singular . 's';
		}
		return $plural;
	}

	public static function singularise($plural) {
		if(substr($plural, -3) == 'ies') {
			$plural = rtrim($plural, 'ies') . 'y';
		} elseif(substr($plural, -1) == 's') {
			$plural = rtrim($plural, 's');
		}
		return $plural;
	}

	public static function strToTime($str) {
		$dateTime = new \DateTime();
		$ts = strtotime($str);
		$today = time() - strtotime('today');
		$ts += $today;
		$dateTime->setTimestamp($ts);
		$ts += date_offset_get($dateTime);
		return $ts;
	}

	public static function formattedDate($ts=null) {
		$timestamp = (isset($ts)) ? $ts : time();
		return strftime(Nn::s('DATE_FORMAT'),$timestamp);
	}

	public static function formattedTime($ts=null) {
		return strftime(Nn::s('TIME_FORMAT'),$timestamp);
	}

	public static function formattedDateTime($ts=null) {
		return strftime(Nn::s('DATETIME_FORMAT'),$timestamp);
	}

	public static function contact_form($fields=array(),$redirect_to=null,$required_fields=array()){
		new ContactForm($fields,$redirect_to,$required_fields);
	}

	public static function ellipsis($text,$char_count=32) {
		$text_array = self::mb_str_split($text);
		$length = count($text_array);
		if($length > $char_count) {
			$text = '';
			for($i=0; $i < $char_count; $i++) {
				$text .= $text_array[$i];
			}
			$text = trim($text).'...';
		}
		return $text;
	}

	public static function slugify($string) {
		$string = iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$string);
		$string = preg_replace('/[\s]/','_',$string);
		$string = preg_replace('/[^a-zA-Z0-9_s-]/','',$string);
		return $string;
	}

	public static function mb_str_split($string) {
		return preg_split('/(?<!^)(?!$)/u',$string);
	}

	public static function noHTML($html_text) {
		return preg_replace("~<[^<]+?>~",'',$html_text);
	}

	public static function currentURL() {
		$http = 'http://';
		if($_SERVER['SERVER_PORT'] != '80') {
			return $http.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
		} else {
			return $http.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
	}

	public static function getSubclassesOf($class,$short=false) {
		$result = array();
		# Make sure all modules are loaded
		foreach(glob(ROOT.DS.'Nn'.DS.'Modules'.DS.'*'.DS.'*.php') as $file) {
			include_once $file;
		}
		foreach(get_declared_classes() as $subclass) {
			if(is_subclass_of($subclass, $class)) {
				if($short) $subclass = static::getShortClassName($subclass);
				$result[] = $subclass;
			} 
		}
		return $result;
	}

	public static function getShortClassName($string) {
		$class_array = explode('\\', $string);
		return end($class_array);
	}

	public static function recursiveRemove($dir) {
		if(file_exists($dir)) {
			$structure = array_diff(scandir($dir),array('.','..'));
			foreach($structure as $file) {
				if(is_dir($dir.DS.$file)) static::recursiveRemove($dir.DS.$file);
				else unlink($dir.DS.$file);
			}
			if(rmdir($dir)) {
				return true;
			}
			return false;
		}
		return true;
	}

	public static function randomString($length=8) {
		$str = '';
		$chars = array_merge(range('A','Z'),range('a','z'),range('0','9'));
		$max = count($chars) - 1;
		for($i=0; $i < $length; $i++) { 
			$rand = mt_rand(0,$max);
			$str .= $chars[$rand];
		}
		return $str;
	}

	public static function is_mobile(){
		if(isset($_SERVER['HTTP_USER_AGENT'])) {
			$ua = $_SERVER['HTTP_USER_AGENT'];
			return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$ua)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($ua,0,4));
		} else {
			return false;
		}
	}

	public static function concat($string,$length) {
		if(strlen($string) > $length) {
			$string = $string;
			$string = substr($string, 0, $length);
			trim($string);
			$lastChar = substr($string, -1);
			if($lastChar == '.') {
				$string .= '..';
			} else {
				$string .= '...';
			}
		}
		return $string;
	}

	public static function flatten(array $array) {
		$result = array();
		$iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
		foreach($iterator as $value) {
			$result[] = $value;
		}
		return $result;
	}

	public static function explode($delimiter, $string) {
		if(!is_string($string) || strlen($string) == 0) return [];
		return array_map('trim',explode($delimiter, $string));
	}

	public static function removeFromArray($array,$val) {
		if(($key = array_search($val, $array)) !== false) {
			unset($array[$key]);
		}
		return $array;
	}

	// function sort_by_date(&$array){
	// 	$cur = 1;
	// 	$stack[1]['l'] = 0;
	// 	$stack[1]['r'] = count($array)-1;
	// 	do {
	// 		$l = $stack[1]['l'];
	// 		$r = $stack[1]['r'];
	// 		$cur--;
	// 		do {
	// 			$i = $l;
	// 			$j = $r;
	// 			$tmp = $array[(int)(($l+$r)/2)];
	// 			do {
	// 				while($array[$i]->timestamp() < $tmp->timestamp())
	// 					$i++;
	// 				while($tmp->timestamp() < $array[$j]->timestamp())
	// 					$j--;
	// 				if($i <= $j){
	// 					$w = $array[$i];
	// 					$array[$i] = $array[$j];
	// 					$array[$j] = $w;
	// 					$i++;
	// 					$j--;
	// 				}
	// 			} while($i <= $j);
	// 			if($i < $r){
	// 				$cur++;
	// 				$stack[$cur]['l'] = $i;
	// 				$stack[$cur]['r'] = $r;
	// 			}
	// 			$r = $j;
	// 		} while($l < $r);
	// 	} while($cur != 0);
	// }

	public static function sortByTimestamp($array) {
		if(count($array) > 1) {
			usort($array,function($a,$b){
				$at = $a['timestamp'];
				$bt = $b['timestamp'];
				if($at == $bt){
					return 0;
				}
				return ($at > $bt) ? -1 : 1;
			});
		}
		return $array;
	}

	public static function sortByDate($array){
		if(count($array) > 1) {
			usort($array,function($a,$b){
				$at = $a->timestamp();
				$bt = $b->timestamp();
				if($at == $bt){
					return 0;
				}
				return ($at > $bt) ? -1 : 1;
			});
		}
		return $array;
	}

	public static function sortByPositionAndDate($array){
		if(count($array) > 1) {
			usort($array,function($a,$b){
				$timediff = $a->timestamp() - $b->timestamp();
				if($timediff) return $timediff;
				if($a->attr('position') && $b->attr('position')) {
					$posdiff = $a->attr('position') - $b->attr('position');
					if($posdiff) return $posdiff;
				}
			});
		}
		return $array;
	}

	public static function RSSservices($url=null, $amt=null) {
		
		if($rawXML = file_get_contents($url)) {
			$xml = new SimpleXmlElement($rawXML);
			
			$services = array();
			
			foreach($xml->channel->item as $item) {
				$service = new service();
				$service->link = $item->link;
				$service->title = $item->title;
				$service->created_at = date(strtotime($item->pubDate));
				$service->ingress = $item->description;
				$services[] = $service;
			}
			
			if(isset($amt)) {
				return array_slice($services,0,$amt);
			} else {
				return $services;
			}
		} else {
			return false;
		}
		
	}

	public static function tweets($url=null) {
		
		$xml = simplexml_load_file($url);
		
		$twitterArray = array();
		
		function title($str=null) {
			$title = $str;
			$wordcount = 2;
			$words = explode(" ", $str);
			if(count($words) > $wordcount) {
				array_splice($words, $wordcount);
				$title = implode(" ", $words)."...";
			}
			return $title;
		}
		
		foreach($xml->status as $item) {
			
			
		
			$service = new service();
			$service->link = 'http://twitter.com/anthon_astrom';
			$service->title = title($item->text);
			$service->source = 'twitter';
	//		$service->author = $dc->creator;
			$service->created_at = date(strtotime($item->created_at));
			$service->ingress = $item->text;
			$services[] = $service;
		}
		
		return $services;
	}

	public static function feeds_and_tweets($arrays=array()) {
		
		$services = array_merge($arrays[0],$arrays[1]);
		
		function cmp($a,$b) {
			if($a->created_at == $b->created_at) {
				return 0;
			}
			return($a->created_at > $b->created_at) ? -1 : 1;
		}
		
		usort($services, 'cmp');
		
		return $services;
	}

	public static function getImageColorFromHex($image,$hex) {
		$bgc_array = str_split($hex);
		if(count($bgc_array) == 3) {
			$red = hexdec($bgc_array[0]).hexdec($bgc_array[0]);
			$green = hexdec($bgc_array[1]).hexdec($bgc_array[1]);
			$blue = hexdec($bgc_array[2]).hexdec($bgc_array[2]);
		} elseif(count($bgc_array) == 6) {
			$red = hexdec($bgc_array[0]).hexdec($bgc_array[1]);
			$green = hexdec($bgc_array[2]).hexdec($bgc_array[3]);
			$blue = hexdec($bgc_array[4]).hexdec($bgc_array[5]);
		}
		return imagecolorallocate($image, $red, $green, $blue);
	}

	public static function mailto($e=null,$lnk=null) {
		$email = (isset($e)) ? $e : Nn::s('MAIL')['FROM_EMAIL'];
		$link = (isset($lnk) ? $lnk : $email);
		$hashed_email = self::characterise($email);
		$hashed_link = self::characterise($link);
		
		$tag = '<a data-pml="'.$hashed_email.'" data-link="'.$hashed_link.'">[protected link]</a>';
		return $tag;
	}

	private static function characterise($str) {
		$chars = str_split($str);
		$strOut = '';
		foreach($chars as $char) {
			$strOut .= ord($char);
			$strOut .= ',';
		}
		$strOut = rtrim($strOut,',');
		return $strOut;
	}

	public static function renderJade($tpl) {
		$pug = new Pug\Pug([
			'prettyprint' => true,
			'extension' => '.jade',
			'cache' => false
		]);
		return $pug->compile($tpl);
	}

	public static function generate_image($str=null, $font=null) {
		$maxColour = 255;
		$size = 120;
		$angle = 0;
		$heightBox = imagettfbbox($size,$angle,$font,"Gg");
		$txtBox = imagettfbbox($size,$angle,$font,$str);
		$width = $txtBox[2] + 46;
		$height = $heightBox[3] - $heightBox[5] + 8;
		$x = 0;
		$y = $size;
		$img = imagecreatetruecolor($width,$height);
		$bgColour = imagecolorallocatealpha($img,0,0,0,0);
		$txtColour = imagecolorallocatealpha($img,$maxColour,$maxColour,$maxColour,0);
	//	imagecolortransparent($img, $txtColour);
		imagettftext($img,$size,$angle,$x,$y,$txtColour,$font,$str);
		imagefill($img,0,0,$bgColour);
		imagealphablending($img,false);
		imagesavealpha($img,true);
		for($i=1; $i<$width; $i++) {
			for($j=1; $j<$height; $j++) {
				$rgb = imagecolorat($img,$i,$j);
				$r = ($rgb >> 16) & 0xFF;
				$g = ($rgb >> 8) & 0xFF;
				$b = $rgb & 0xFF;
				if($r > 0){
					$alpha = round(($r/255)*127);
					imagesetpixel($img,$i,$j,imagecolorallocatealpha($img,0,0,0,$alpha));
				}
			}
		}
		imagepng($img, $str.".png");
		echo "<img src=\"".$str.".png"."\">";
		imagedestroy($img);
	}

	public static function UIIcon($type=null) {
		$basename = str_replace(" ","_",strtolower($type));
		$base_path = ROOT.DS.'public'.DS.'backnn'.DS.'imgs'.DS.'static'.DS.'ui'.DS.$basename;
		$base_uri = Nn::s('DOMAIN').'/backnn/imgs/static/ui/'.$basename;
		if(file_exists($base_path.'.svg')) {
			$src = $base_uri.'.svg';
			return '<img src="'.$src.'" alt="'.$type.'" />';
		} elseif(file_exists($base_path.'.gif')) {
			$src = $base_uri.'.gif';
			return '<img src="'.$src.'" alt="'.$type.'" />';
		}
		return false;
	}

}
		
	?>