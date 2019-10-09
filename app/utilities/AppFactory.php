<?php

namespace App\utilities; 



class AppFactory{

	public static $_instance; 

	public static function getInstance(){
		if (is_null(self::$_instance)){
			self::$_instance = new AppFactory(); 
		}
	return self::$_instance; 
	}



	public static function getController($name){
		$className = '\\App\\controller\\'.ucfirst($name).'Controller'; 
		return new $className; 
	}

	public static function getModel($name){ 
		$className = '\\App\\model\\'.ucfirst($name).'Model';
		return new $className; 
	}

}