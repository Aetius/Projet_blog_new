<?php
namespace App\Model;


use App\Utilities\ErrorException;
use App\Config\Constants;

class BDDConnection{
	private static $_bdd = null;

    /**
     * @return \PDO|null
     * @throws ErrorException
     */
    public static function connection(){
		if ((self::$_bdd)=== null){
			$dsn = Constants::DSN;
			$user = Constants::USER_NAME_DB;
			$password = Constants::PASSWORD_DB;
			$options=[\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION];

			try{
				self::$_bdd = new \PDO($dsn, $user , $password, $options);
			}catch (\PDOException $e){

			  throw new ErrorException("L'accès à la bdd est refusé");
				
			}
			
		}
		return self::$_bdd;
	}

}
		