<?php
namespace App\Utilities;

class Session{
	
	private static $lifetime=10000000; //don't forget to change this value.

	public static function getSession(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
		
		setcookie(session_name(),session_id(),time()+self::$lifetime);
	
	}
}