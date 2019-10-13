<?php
namespace App\controller; 

use App\model\UserModel;
use App\controller\Controller; 




class UserController extends Controller{
	
	private $modelUser; 


	public function __construct(){
		$this->modelUser= new UserModel();
		parent::__construct(); 
	}


/*show functions */

	public function showConnexion(){
		if (isset($_SESSION['access'])){
			if (($_SESSION['access']['auth'])==='valide'){
				header('Location:/admin/dashboard');
			}	
		}
		$this->show("connexionPage");
	}

	public function showInscription(){ 
		$this->show("inscriptionPage");
	}


	public function showSettings(){	
		$this->show('settings');
	}



	public function showPage(){
		$parts = explode("::", __METHOD__);
		$page = $parts[1]; 
		$this->show($page."Page");
	}


	public function showDashboardAdmin(){
		$results['users']=$this->modelUser->all(); 
		$this->show('users', $results); 
	}


	public function showLost(){
		$this->show('password');
	}


/*
* verify user's access in the auth utilities.
**/
	public function editorAccess(){ 
		if (array_key_exists('access', $_SESSION)) {
			if ($_SESSION['access']['auth'] ==='valide'){
				$login = $_SESSION['access']['login'];
				($_SESSION['user']=$this->modelUser->one('login', $login)); 
				return true;
			}
		}
	}


	public function adminAccess(){
		if (array_key_exists('user', $_SESSION)){
			if ($_SESSION['user']['is_admin']==="1"){
				return true; 
			}
		}
	}
	



/*creation update and delete users*/
	public function inscription(){ 
		$inputs= $this->modelUser->hydrate($_POST);
		
		if($this->modelUser->inscription($inputs)=== true){ 
			$_SESSION['success'][1]="L'inscription est effectuée !";
			header('Location:/admin');
		};
		$results['saveInputUser']=$this->modelUser->saveInput();
		$results['errors'] = $this->modelUser->errors();
		$this->show("inscriptionPage", $results);		
	}


	public function settings(){ 
		$id = $_SESSION['user']['id']; 
		$inputs = $this->modelUser->hydrate($_POST); 

		if (array_key_exists("email", $inputs)){
			$this->update('email', $id);
		}elseif (array_key_exists("password", $inputs, "settings")) {
			$this->update('password', $id);
		}elseif (array_key_exists("activate", $inputs, "settings")) {  
			$this->desactivate($id);		
		}else{
			header('Location:/admin/settings');
		}
		
	}


	public function dashboardAdmin(){
		$id = $_POST['id']; 
		$inputs = $this->modelUser->hydrate($_POST);
		$this->update('account', $id, "users"); 


	}


	private function update($label, $id, $page){
		$method = "update".(ucfirst($label)); 
		$result = $this->modelUser->$method($id, $label);  
		
		if( $result !== true){
			$_SESSION['success'][2]= "La modification a échouée"; 
			$results["errors"]=$result;
			$this->show($page, $results);
		} else{
			$_SESSION['success'][1]="La modification a bien été prise en compte"; 
			$this->editorAccess(); 
			header("location:/admin/$page");
		}
	}


	private function desactivate($id){ 
		$input = $_POST; 
		$input['id']=$id;  
		$this->modelUser->hydrate($input); 
		if ($this->modelUser->desactivate($id)){
			unset ($_SESSION['access']);
			unset ($_SESSION['user']);
			$_SESSION['success'][1]="La désactivation du compte a bien été prise en compte. Pour le réactiver ou le supprimer, merci de contacter l'administrateur du site. "; 
			header('location:/admin');
		}else{
			$_SESSION['success'][2]="Erreur lors de la désactivation. Merci de contacter l'administrateur du site.";
			header('location:/admin/settings');
		}
	}


/*	
	private function updateError(){
		if (empty($this->modelUser->errors())){
			return false;
		}elseif (!(empty($this->modelUser->errors()))) {	
			$_SESSION['success']=2;
			$errorResult['errors'] = $this->modelUser->errors();
			$this->show("settingsPage", $errorResult);
			$errorResult=[];		
		}
	}*/






/*connexion, logout and verification of login and password*/
	public function connexion(){ 
		$this->modelUser->hydrate($_POST);
		$result = $this->modelUser->connexion();
		if ($result === true){
			$_SESSION['user']=$this->modelUser->one('login', $this->modelUser->login());  
			if ($_SESSION['user']['activate'] == 1){
				$_SESSION['access']=['auth'=>'valide', 'login'=>$this->modelUser->login()];
				$_SESSION['success'][1]= "Vous êtes connecté"; 
				return header('location: admin/dashboard');
			}
		}
		$_SESSION['success'][2]= "Le couple identifiant/mot de passe est incorrect"; 
		$this->show('connexionPage');
	}


	public function lostPassword(){
		$inputs=$this->modelUser->hydrate($_POST); 
		var_dump("en profiter pour refaire la fonction email. vérifier le mail et le login. une fois ok => on génère un nouveau mot de passe dans le model user et on envoie sur le model email pour envoyer le nouveau mot de passe. 
			en profiter pour refaire le modelmail. 
			pour le login : je ne sais pas quel niveau de sécu je mets. mais même logique. donc faire une fonction récup password générique.")
		var_dump($inputs); die(); 

	}


	public function logout(){
		session_destroy();
		sessionController::getSession(); 
		$_SESSION['success'][1]= "La session est bien déconnectée"; 
		header('Location:/admin');
	}

	/*public function defineUser($userId){ 
		$userParams = $this->modelUser->one('id', $userId); 
		$user=$userParams['login']; 
		return $user; 
	}*/

}