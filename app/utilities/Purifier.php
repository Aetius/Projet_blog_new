<?php

namespace App\utilities;

require_once '../vendor/ezyang/htmlpurifier/library/HTMLPurifier.auto.php';

class Purifier{

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


	public static function htmlPurifier($dirty_html){

		$config = \HTMLPurifier_Config::createDefault();
		$purifier = new \HTMLPurifier($config);
		return ($clean_html = $purifier->purify($dirty_html));
		
	}



}