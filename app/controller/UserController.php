<?php
namespace App\controller; 


use App\model\UserModel;
use App\utilities\Verification;

class UserController{
	private $modelUser; 
	private $session; 
	private $errors=[];

	public function __construct(){
		$this->modelUser= new UserModel();
		session_start();
	}


/*show functions */

	public function showConnexion(){
		if (isset($_SESSION['access'])){
			if (($_SESSION['access']['auth'])==='valide'){
				$this->show('dashboard');
			}	
		}else {
			$this->show("connexion");
		}
	}

	public function showInscription(){
		$this->show("inscription");
	}

	public function showDashboard(){		
		if (array_key_exists('access', $_SESSION)) {
			if ($_SESSION['access']['auth'] ==='valide'){
				$login = $_SESSION['access']['login'];
				var_dump($login);
				die();
/////////ici. 
/////à faire : récupérer les données du user, les mettre dans le dashboard, et config de la modif. 
				$this->show('dashboard', $this->getData($login));
			}
		}else{
			header('Location:/connexion');
		}
	}

	private function show($id, $result=[]){
		$twig = new \View\twigView();
		$twig->show("/user/$id".'Page', $result);
	}

/*creation and delete users*/
	public function inscription(){
		$this->hydrateUser();
		if (empty($this->errors)){
			if($this->modelUser->inscription()=== true){
				header('Location:/connexion');
			}else{
				header('Location:/inscription');
			}
		}else{
			$errorInscriptionResult['saveInputUser']=$this->modelUser->saveInput();
			$errorInscriptionResult['errors'] = $this->errors;
			$this->show("inscription", $errorInscriptionResult);
			$errorInscriptionResult=[];		
		}
	}


/*connexion, logout and verification of login and password*/
	public function connexion(){
			$this->hydrateUser();
			$this->modelUser->connexion();

			if ((empty($this->error))&& ($this->modelUser->connexion() === true)){
				$_SESSION['access']=['auth'=>'valide', 'login'=>$this->modelUser->login()];
				header('location: connexion/dashboard');
			}else{
			$this->show('connexion', 'Identifiant et/ou Mot de passe invalide');
			}
	}


	public function logout(){
		session_destroy();
		header('Location:/connexion');
	}


/*          */



/*hydrate user in UserModel. use for connexion and inscription. */	

//////voir si ca fonctionne. sinon, fonction hydrate dessous. 
/*	private function hydrateUser($id){
		foreach ($_POST as $key => $value) {
			$key = Verification::input($key);
			$value = Verification::input($value); 
			$name = "set".$key;
			$this->__set($name, $value);
			if ((!($this->modelUser->error)=="")&& (!(in_array($this->modelUser->error, $this->error)))){
				 (array_push($this->_error, $this->_modelUser->error)); 
			};
		}
	}

	private function __set($name, $value){
		return $this->modelUser->$name($value, $id);
	}*/



	private function hydrateUser(){
		foreach ($_POST as $key => $value) {
			$key = Verification::input($key);
			$value = Verification::input($value);  /////vérif avec les setters dans le model. nécessaire de le refaire dans le setter ? 
			$name = "set".$key;
			if (method_exists($this->modelUser, $name)){ 
				$this->modelUser->$name($value, $key); 
			}
			}

			if ((!($this->modelUser->error)=="")&& (!(in_array($this->modelUser->error, $this->errors)))){
				 (array_push($this->errors, $this->modelUser->error)); 
			}
			
			
	}



}