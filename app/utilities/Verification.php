<?php

namespace App\utilities;

class Verification{
	public static function input($strVerify){
		$strVerify=htmlspecialchars($strVerify);
		if (is_numeric($strVerify)){
			return (intval($strVerify));
		}else{
			$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a", "<", ">");
	        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z", "\<\ ", "\>\ ");
	        return str_replace($search, $replace, $strVerify);
        }
	}

	public static function text($strVerify){
			$strVerify=htmlspecialchars($strVerify);
			$search = array( '"', "\\",  "\x00", "\n",  "\r",  "\x1a", "/", "\\");
		    $replace = array(" ' ", "", "", "", "", "", "", "");
			return str_replace($search, $replace, $strVerify);
	}

	public static function name($name){
		if(preg_match("#^[a-zA-Z]{2,}$#", $name)){
			return 'valide';
		}else{
			return 'non valide';
		}
	}

	public static function mail($mail){
		if (preg_match("#^[a-zA-Z0-9\.]{2,}[@]{1}[a-z]{1,20}\.[a-z]{2,8}$#", $mail)){
			return 'valide';
		}else{
			return 'non valide';
		}
	}

	public static function password($password){
		if (preg_match("#^[a-zA-Z(0-9)+(,?.;!%@&)+]{8,}$#", $password)){
			return 'valide';
		}else{
			return 'non valide';
		}
	}

}