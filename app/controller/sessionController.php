<?php
namespace App\controller; 

class sessionController{
	
	private static $lifetime=10000000; 

	public static function getSession(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
		
		setcookie(session_name(),session_id(),time()+self::$lifetime);
	
	}
}