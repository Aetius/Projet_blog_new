<?php
namespace App\model;
use PDO;
//créer un utilisateur pour la lecture seule, un pour l'ajout des commentaires, et un pour l'écriture newsletter. 

class BDDConnection{
	private static $_bdd;

	public static function Connection(){
		$pdo=new PDO('mysql:host=localhost;dbname=projet_p5_blog_2;charset=utf8', 'root', ''); 
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
		return $pdo; 
	}
}

		/*try {
			self::$_bdd = new PDO('mysql:host=localhost;dbname=projet_p5_blog_2;charset=utf8', 'root', ''); 
			return self::$_bdd;
		} catch (Exception $e) {
			 die('Erreur : ' . $e->getMessage());
		}
	}*/





//////pour mettre par défaut les valeurs de retour sous forme d'objet : 
// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

//Pour mettre par défaut une exception : 
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);