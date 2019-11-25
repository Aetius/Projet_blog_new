<?php
namespace App\Utilities;

use App\Config\Constants;

class Session{

	public static function getSession(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
		$lifetime = Constants::COOKIE_LIFE_TIME;

		setcookie(session_name(),session_id(),time()+$lifetime);
	
	}
}