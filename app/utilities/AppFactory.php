<?php

namespace App\Utilities;



class AppFactory{

	public static $_instance; 

	/**
	 *Get instance of AppFactory. Singleton
	 * @return AppFactory
	 */
	public static function getInstance(){
		if (self::$_instance === null){
			self::$_instance = new AppFactory(); 
		}
		return self::$_instance; 
	}


	/**
	 *Find the controller
	 *@param string $name
	 *@return  $className
	 */
	public static function getController($name){
		$className = '\\App\\controller\\'.ucfirst($name).'Controller';
        return new $className;
	}

	/**
	 *Find the model
	 *@param string $name
	 *@return $classname
	 */
	public static function getModel($name){ 
		$className = '\\App\\model\\'.ucfirst($name).'Model';
		return new $className; 
	}

}