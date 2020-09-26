<?php
	namespace App;

	class Utilities {
		public static function isJson($string): bool {
			return ((is_string($string) && (is_object(json_decode($string)) || is_array(json_decode($string))))) ? true : false;
		}

		public static function dataFilter($string = "", $db_link = null) {
			//\App\Utilities::dataFilter
			$string = strip_tags($string);
			$string = stripslashes($string);
			$string = htmlspecialchars($string);
			$string = trim($string);
			if(isset($db_link) && $db_link != null) {
				$string = $db_link->filter_string($string);
			}
			return $string;
		}

		public static function cURL($url, $ref, $header, $cookie, $p=null) {
			$ch =  curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			if(isset($_SERVER['HTTP_USER_AGENT'])) {
				curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			}
			if($ref != '') {
				curl_setopt($ch, CURLOPT_REFERER, $ref);
			}
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			if($cookie != '') {
				curl_setopt($ch, CURLOPT_COOKIE, $cookie);
			}
			if ($p) {
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
			}
			$result =  curl_exec($ch);
			curl_close($ch);
			if ($result){
				return $result;
			} else {
				return '';
			}
		}

		public static function curlGET($url) {
			return Utilities::cURL($url, '', '', '');
		}

		public static function generateCode($length = 6): string {
			// \App\Utilities::generateCode
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
			$code = "";
			$clen = strlen($chars) - 1;
			while (strlen($code) < $length) {
				$code .= $chars[mt_rand(0, $clen)];
			}
			return $code;
		}

		//return: mixed
		public static function checkFields($arr = [], $keysArr = [], $errCode = "error", $db_link = null, $ignore_errors = false) {
			$data = [];
			foreach ($keysArr as $key) {
				if(!isset($arr[$key]) || (empty($arr[$key]) && $arr[$key] != "0" && $arr[$key] != 0)) {
					if(!$ignore_errors) {
						exit($errCode.' ('.$key.' is empty)');
					}
				} else {
					$data[$key] = Utilities::dataFilter($arr[$key], $db_link);
				}
			}
			return $data;
		}

		public static function checkINT($value = 0, $db_link = null): int {
			$value = Utilities::dataFilter($value, $db_link) + 0;
			if(!is_int($value)) {
				$value = 0;
			}
			return $value;
		}

		public static function checkFloat($value = 0, $db_link = null): float {
			$value = floatval(Utilities::dataFilter($value, $db_link));
			if(!is_float($value)) {
				$value = 0;
			}
			return $value;
		}

		public static function checkINTFields($arr = [], $keysArr = [], $db_link = null): array {
			//$db_link - ссылка на экземпляр \App\DataBase
			$data = [];
			foreach ($keysArr as $key) {
				if(!isset($arr[$key]) || empty($arr[$key])) {
					$data[$key] = 0;
				} else {
					$data[$key] = Utilities::checkINT($arr[$key], $db_link);
				}
			}
			return $data;
		}
		
		public static function jsonReadableEncode($in, $indent = 0, $from_array = false)
		{
			//\App\Utilities::jsonReadableEncode
			//$_myself = __FUNCTION__;

			$out = '';

			foreach ($in as $key => $value)
			{
				$out .= str_repeat("\t", $indent + 1);
				$key_escaped = Utilities::jsonEscape((string) $key);
				$escape_symb = '"';
				//if(is_int($key)) {
				//	$escape_symb = '';
				//}
				$out .= $escape_symb . $key_escaped . $escape_symb . ': ';

				if (is_object($value) || is_array($value))
				{
					$out .= "\n";
					$out .= Utilities::jsonReadableEncode($value, $indent + 1);
				}
					elseif (is_bool($value))
				{
					$out .= $value ? 'true' : 'false';
				}
					elseif (is_null($value))
				{
					$out .= 'null';
				}
					elseif (is_string($value))
				{
					$out .= "\"" . Utilities::jsonEscape($value) ."\"";
				}
					else
				{
					$out .= $value;
				}

				$out .= ",\n";
			}

			if (!empty($out))
			{
				$out = substr($out, 0, -2);
			}

			$out  = str_repeat("\t", $indent) . "{\n" . $out;
			$out .= "\n" . str_repeat("\t", $indent) . "}";

			return $out;
		}
		
		public static function jsonEscape($json) {
			//\App\Utilities::jsonEscape
			return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $json);
		}
	}
