<?php
namespace App\Model;


use App\Utilities\ErrorException;

class BDDConnection{
	private static $_bdd = null;

	public static function connection(){
		if ((self::$_bdd)=== null){
			$dsn = 'mysql:host=localhost;dbname=projet_p5_blog_oc;charset=utf8';
			$user = 'root';
			$password = 'root';
			$options=[\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION];

			try{
				self::$_bdd = new \PDO($dsn, $user , $password, $options);
			}catch (\PDOException $e){

			  throw new ErrorException("L'accès à la bdd est refusée");
				
			}
			
		}
		return self::$_bdd;
	}

}
		