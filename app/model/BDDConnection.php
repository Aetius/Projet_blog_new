<?php
namespace App\model;


class BDDConnection{
	private static $_bdd = null;

	public static function connection(){
		if (is_null(self::$_bdd)){
			$dsn = 'mysql:host=localhost;dbname=projet_p5_blog_oc;charset=utf8';
			$user = 'root';
			$password = 'root';
			$options=[\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION];

			try{
				self::$_bdd = new \PDO($dsn, $user , $password, $options);
			}catch (\PDOException $e){
				die ("L'accès à la bdd est refusée");
				
			}
			
		}
		return self::$_bdd;
	}

}
		