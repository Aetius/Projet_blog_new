<?php
namespace App\controller; 


use App\controller\ArticleController;
use App\model\UserModel;
use App\utilities\Purifier;
use App\controller\TwigController; 
use App\controller\Controller; 

class UserController extends Controller{
	private $modelUser; 
	private $session; 
	private $errors=[];

	public function __construct(){
		$this->modelUser= new UserModel();
		sessionController::getSession(); 
	}


/*show functions */

	public function showConnexion(){
		if (isset($_SESSION['access'])){
			if (($_SESSION['access']['auth'])==='valide'){
				header('Location:/connexion/dashboard');
			}	
		}
		$this->show("connexionPage");
	}

	public function showInscription(){ 
		$this->show("inscriptionPage");
	}


/*
	public function showDashboard(){
		
	
			$articleController=new ArticleController();
			$articleController->showDashboard();
		
	}*/

	public function showSettings(){	
		if ($this->admin() ===true){
			$this->show('settingsPage');
		}
	}

	public function showPage(){
		$parts = explode("::", __METHOD__);
		$page = $parts[1]; 
		$this->show($page."Page");

	}


/*
* verify user's access in the auth utilities.
**/
	public function admin(){
		if (array_key_exists('access', $_SESSION)) {
			if ($_SESSION['access']['auth'] ==='valide'){
				$login = $_SESSION['access']['login'];
				$this->getData($login);
				return true;
			}
		}else{
			header('Location:/connexion');
		}
	}



/*creation update and delete users*/
	public function inscription(){ 
		$inputs= $this->modelUser->hydrate($_POST);
		
		if($this->modelUser->inscription($inputs)=== true){ 
			$_SESSION['success'][1]="L'inscription est effectuée !";
			header('Location:/connexion');
		};
		
		$results['saveInputUser']=$this->modelUser->saveInput();
		$results['errors'] = $this->modelUser->errors();
		$this->show("inscriptionPage", $results);		
	}


	public function update(){
		$login = $_SESSION['access']['login'];    
		$allKey = $this->modelUser->hydrate(); 

		if (in_array("Mail", $allKey)){
			$this->updateMail('mail', $login);
		}elseif (in_array("Password", $allKey)) {
			$this->updatePassword('password', $login);
		}elseif (in_array("Delete", $allKey)) {
			$this->delete($login);		
		}else{
			header('Location:/connexion/settings');
		}
		unset ($_SESSION['success']);
	}

	private function updateMail($label, $login){
		$this->modelUser->duplication(); 
		if (($this->updateError()===false) && ($this->modelUser->updateUser($login, $label)===true)){
			$_SESSION['success']=1;
			$this->show('settingsPage', $this->getData($login));
		}
	}
/////////////////////:à refaire
	private function updatePassword($label, $login){
		$this->modelUser->oldPasswordConfirm($login);
		if (($this->updateError()===false)&&($this->modelUser->updateUser($login, $label)===true)){
			$_SESSION['success']=1;
			$this->show('settingsPage', $this->getData($login));
		}
		/*if ($this->modelUser->oldPasswordConfirm($login)!=true){
			$this->errors['password']="L'ancien mot de passe n'est pas valide";
			$this->updateError();
		}elseif (($this->updateError()===false)&&($this->modelUser->updateUser($label, $login)===true)){
			$_SESSION['success']=1;
			$this->show('settings', $this->getData($login));
		}*/
	}
	
	private function updateError(){
		if (empty($this->modelUser->errors())){
			return false;
		}elseif (!(empty($this->modelUser->errors()))) {	
			$_SESSION['success']=2;
			$errorResult['errors'] = $this->modelUser->errors();
			$this->show("settingsPage", $errorResult);
			$errorResult=[];		
		}
	}


	private function delete($login){
		if ($this->modelUser->delete($login) ===true){
			unset ($_SESSION['access']);
			unset ($_SESSION['user']);
			$result="La session a bien été supprimée!!!";
			$twig = new \View\twigView();
			$twig->show('homePage', $result);
		}
	}


/*connexion, logout and verification of login and password*/
	public function connexion(){
			$this->modelUser->hydrate();
			$this->modelUser->connexion();
			if ((empty($this->modelUser->errors()))&& ($this->modelUser->connexion() === true)){
				$_SESSION['access']=['auth'=>'valide', 'login'=>$this->modelUser->login()];
				header('location: connexion/dashboard');
			}else{
			$this->show('connexionPage', 'Identifiant et/ou Mot de passe invalide');
			}
	}


	public function logout(){
		session_destroy();
		header('Location:/connexion');
	}

	public function defineUser($userId){ 
		$userParams = $this->modelUser->one('id', $userId); 
		$user=$userParams['login']; 
		return $user; 
	}


/*          */



/*hydrate user in UserModel.  */	


//////voir si ca fonctionne. sinon, fonction hydrate dessous. 
/*	private function modelUser->hydrate($id){
		foreach ($_POST as $key => $value) {
			$key = Purifier::input($key);
			$value = Purifier::input($value); 
			$name = "set".$key;
			$this->__set($name, $value);
			if ((!($this->modelUser->modelUser->errors)=="")&& (!(in_array($this->modelUser->modelUser->errors, $this->modelUser->errors)))){
				 (array_push($this->_error, $this->_modelUser->modelUser->errors)); 
			};
		}
	}

	private function __set($name, $value){
		return $this->modelUser->$name($value, $id);
	}*/
	


/*
	private function hydrate(){
		$allKey=[];
		foreach ($_POST as $key => $value) {
			$key = Purifier::input($key);
			$value = Purifier::input($value);  
			$name = "set".$key;
			array_push($allKey, $key);
			if (method_exists($this->modelUser, $name)){ 
				$this->modelUser->$name($value, $key); 
			}
			
		}if ((!($this->modelUser->modelUser->errors())=="")){
				$this->errors= $this->modelUser->modelUser->errors(); 
		}
		return $allKey;
	}*/

	
/*verify if mail or login are already in bdd*/
	/*private function duplication(){
		if (!empty($this->modelUser->dataInBdd())){
			$inBdd=$this->modelUser->dataInBdd();
				
			foreach ($inBdd as $key => $value) {
				if ($key ==="Login") {
					$this->errors['Login']="Le login est déjà utilisée";
				}elseif ($key === "Mail"){
					$this->errors['Mail']="L'adresse mail est déjà utilisée";
				}
			}
		}
	}*/

/*add the login in $_SESSION*/
	private function getData($login){
		$login=Purifier::htmlPurifier($login);
		return($_SESSION['user']=$this->modelUser->one('login', $login));
	}




	/*start $_session if it is not already launch*/
/*
	private function session(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
	}*/

}