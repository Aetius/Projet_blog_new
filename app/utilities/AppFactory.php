<?php

namespace App\utilities; 



class AppFactory{

	public static $_instance; 

	/**
	 *Get instance of AppFactory. Singleton
	 *@return instance.
	 */
	public static function getInstance(){
		if (is_null(self::$_instance)){
			self::$_instance = new AppFactory(); 
		}
		return self::$_instance; 
	}


	/**
	 *Find the controller
	 *@param str $name
	 *@return controller
	 */
	public static function getController($name){
		$className = '\\App\\controller\\'.ucfirst($name).'Controller'; 
		return new $className; 
	}

	/**
	 *Find the model
	 *@param string $name
	 *@return model
	 */
	public static function getModel($name){ 
		$className = '\\App\\model\\'.ucfirst($name).'Model';
		return new $className; 
	}

}