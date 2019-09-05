<?php
namespace App\model;
use PDO;
//créer un utilisateur pour la lecture seule, un pour l'ajout des commentaires, et un pour l'écriture newsletter. 

class BDDConnection{
	private static $_bdd;

	public static function connection(){
		if (is_null(self::$_bdd)){
			new BDDConnection;  //on lance la fonction construct de la class
			return self::$_bdd; //on retourne le résultat. si on fait self::$_bdd=new BDDConnection;, on retourne la classe et non l'instance de PDO.
		}
		return self::$_bdd;
	}

//pour se connecter : BDDConnection::getInstance();

	private function __construct(){
		$dsn = 'mysql:host=localhost;dbname=projet_p5_blog_2;charset=utf8';
		$user = 'root';
		$password = '';
		$options=[PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION];
		$options=[PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ];
		
		try{
			self::$_bdd = new PDO($dsn, $user , $password, $options);	
		}catch (\PDOException $e){
			die ("L'accès à la bdd est refusée");
			
		}
	}
}
		/*try {
			self::$_bdd = new PDO('mysql:host=localhost;dbname=projet_p5_blog_2;charset=utf8', 'root', ''); 
			return self::$_bdd;
		} catch (Exception $e) {
			 die('Erreur : ' . $e->getMessage());
		}
	}*/



/*fonction destruction à mettre en place ????
    public static function destructToken() {        
        if(isset(self::$conn)) { // si l'instance de la connexion est en cours
            return self::$conn = null; // ... alors on la supprime
        }
    }
    // ... cette méthode est appelée de façon statique par la méthode magique __destruct()
    public function __destruct() {        
        self::destructToken();

*/

//////pour mettre par défaut les valeurs de retour sous forme d'objet : 
// $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

//Pour mettre par défaut une exception : 
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);