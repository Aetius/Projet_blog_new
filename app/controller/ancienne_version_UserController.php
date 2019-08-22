<?php
namespace App\controller; 


use App\model\UserModel;
use App\utilities\Verification;
use App\controller\SessionController; 

class UserController{
	private $_modelUser; 
	private $_session;
	private $_name; 
	private $_lastName;
	private $_mail;
	private $_login;
	private $_password;
	private $_role='comment';
	private $_error = [];
	public $userInBDD;
	private $_showPage='';

public function __construct(){
	$this->_modelUser= new UserModel();
	$this->_session = new SessionController();
}

public function show(){
	$url=explode('/',$_SERVER['REQUEST_URI']);
	if (($url[1] =="connexion") && (count($url)>2)){
		session_start();
		SessionController::connexionSession();
	}
	$url=$url[count($url)-1];		
	$twig =new \View\twigView();
	$twig->show("/$url"."Page");
}

public function logout(){
	SessionController::logout();
	header('Location:/connexion');
}



//les valeurs du constructeur sont initialisées avec les mutateurs correspondants : principe d'encapsulation. 
////////////////////fonction redirection et confirmation que l'enregistrement a bien été fait. 


/////////hydratation des données récupérées via les setters du modèle. 



///loop function that verify if inscription's datas are ok. if not, post an error message. ////////
public function inscription(){   
	$this->hydrateUser("inscription"); 
	if (empty($this->_error)){
		$this->_modelUser->create();
		echo 'inscription terminée'; 
		header('Location:/connexion');
	}else{
		print_r ($this->_error);
		header('Location:/inscription');
	}
}

///////////////////	VERIFIER QUE LES EDITOR NE PEUVENT PAS ALLER SUR L'ESPACE ADMIN GENERAL./////////////////////////////
public function connexion(){  
	$this->hydrateUser('connexion');
	if ((empty($this->_error))&& ($this->_modelUser->connexion() == true)){
		$this->_session->run($this->_modelUser->name(), $this->_modelUser->role());
		if ($this->_modelUser->role() == 'admin'){
			session_start();
			header('Location:/connexion/espace');
		}elseif ($this->_modelUser->role() == 'editor') {
			echo'editor';
			////redirection vers page edition commentaires
		}
		echo "connexion réussie";
		
	}else {
		echo "Identifiant et/ou Mot de passe invalide";
		$this->show();
		////même page avec affichage erreur.
	}
/*
	$testLogUser = $this->setLogin();
	$testPswUser = $this->setVerifPassword();
	$this->_modelUser->read($this);
	$testLogBdd = $this->_modelUser-> bddUserLogin();
	$testPswBDD = $this->_modelUser-> bddUserPassword();

	if ((strcmp($testLogBdd, $testLogUser)==0) && (password_verify($testPswUser, $testPswBDD))) {
		echo 'Vous êtes connecté';
		if ($this->_modelUser->bddUserRole() == 'admin'){
			require ("../view/adminArea.php");
		}elseif ($this->_modelUser->bddUserRole() == 'comment') {
			$session = new SessionController(); 
			$session->sessionStart($testLogUser); 
			
			require ("../view/memberArea.php");
		}	
	}else{
		echo "Identifiant ou mot de passe invalide";
		require ("../view/connexionPage.php");
	}*/
	}

	public function modification(){
		
	}

	public function delete(){

	}


	//////////////getters/////////////////

	public function name(){
		
		return $this->_name;
	}

	public function lastName(){
		
		return $this->_lastName;
	}

	public function mail(){
		return $this->_mail;
	}

	public function login(){
		return $this->_login;
	}

	public function password(){
		return $this->_password;
	}

	public function role(){
		return $this->_role;
	}



//voir pour définir les rôles.



	private function hydrateUser($id){
		foreach ($_POST as $key => $value) {
			$key = Verification::input($key);
			$value = Verification::input($value);  /////vérif avec les setters dans le model. nécessaire de le refaire dans le setter ? 
			$name = "set".$key;
			if (method_exists($this->_modelUser, $name)){ 
				$this->_modelUser->$name($value, $id); 
			};
			if ((!($this->_modelUser->error)=="")&& (!(in_array($this->_modelUser->error, $this->_error)))){
				 (array_push($this->_error, $this->_modelUser->error)); 
			};
		}	
	}
}

