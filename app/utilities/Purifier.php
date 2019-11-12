<?php

namespace App\utilities;

class Purifier{
	public static function htmlPurifier($dirty_html){
		
		$config = \HTMLPurifier_Config::createDefault();
		$purifier = new \HTMLPurifier($config);
		return ($purifier->purify($dirty_html));
		
	}

}