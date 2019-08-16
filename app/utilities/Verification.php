<?php

namespace App\utilities;

class Verification{
	public static function input($strVerify){
		if (is_numeric($strVerify)){
			return (intval($strVerify));
		}else{
			$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a", "<", ">");
	        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z", "\<\ ", "\>\ ");
	        return str_replace($search, $replace, $strVerify);
        }
	}

	public static function text($strVerify){
			$search = array( '"', "\\",  "\x00", "\n",  "\r",  "\x1a", "/", "\\");
		    $replace = array(" ' ", "", "", "", "", "", "", "");
			return str_replace($search, $replace, $strVerify);
	}

	public static function name($name){
		if(preg_match("#^[a-zA-Z]*$#", $name)){
			return 'valide';
		}else{
			return 'non valide';
		}
	}

	public static function mail($mail){
		if (preg_match("#^[a-zA-Z0-9\.]*[@]{1}[a-z]{1,20}\.[a-z]{2,8}$#", $name)){
			return 'valide';
		}else{
			return 'non valide';
		}
	}

}