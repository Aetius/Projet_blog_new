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


	public function showSettings($results=[]){	
		$this->show('settings');
	}


	public function showPage(){
		$parts = explode("::", __METHOD__);
		$page = $parts[1]; 
		$this->show($page."Page");
	}


	public function showDashboardAdmin($results=[]){
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
		$results = $this->modelUser->lostPassword(); 
		
		if ($results !== false){
			$modelEmail = $this->factory->getModel('Email'); 
			$modelEmail->preparePassword($results, $inputs); 
			header('location:/admin'); 
		}else{
			$_SESSION['success'][2]= "Login ou adresse email invalide"; 
			header("location:/password"); 
		}
	}


	public function logout(){
		session_destroy();
		sessionController::getSession(); 
		$_SESSION['success'][1]= "La session est bien déconnectée"; 
		header('Location:/admin');
	}


/*creation update and delete users*/
	public function inscription(){ 
		$inputs= $this->modelUser->hydrate($_POST);
		$results = $this->modelUser->prepareInscription($inputs); 
		if($results == null){ 
			$_SESSION['success'][1]="L'inscription est effectuée !";
			return header('Location:/admin/users');
		}else{
			$_SESSION['success'][2]="L'inscription a échoué !";
			$this->show("inscriptionPage", $results);		
		}
	}


	public function settings(){ 
		$input['id'] = $_SESSION['user']['id']; 
		$inputs = $this->modelUser->hydrate($input);
		$inputs = $inputs + $this->modelUser->hydrate($_POST);  

		if (array_key_exists("email", $inputs)){
			$this->update('email', "settings");
			$this->showSettings($results); 
		}elseif (array_key_exists("password", $inputs)) {
			$this->update('password', "settings");
			$this->showSettings($results); 
		}elseif (array_key_exists("activate", $inputs)) {  
			$this->desactivate();	
		}else{
			header('Location:/admin/settings');
		}
		
	}


	public function dashboardAdmin(){ 
		$input['id'] = $_SESSION['user']['id']; 
		$inputs = $this->modelUser->hydrate($input);
		$inputs = $inputs + $this->modelUser->hydrate($_POST);   
		$results = $this->update('account', "users");
		$this->showDashboardAdmin($results); 
	}


	private function update($label, $page){
		$method = "update".(ucfirst($label)); 
		$results = $this->modelUser->$method($label);  
		
		if( $results !== null){
			$_SESSION['success'][2]= "La modification a échouée"; 
			return $results;
		} else{
			$_SESSION['success'][1]="La modification a bien été prise en compte"; 
			$this->editorAccess(); 
			return header("location:/admin/$page");
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





}