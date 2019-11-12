<?php
namespace App\controller; 

use App\model\UserModel;
use App\controller\Controller;
use Psr\Http\Message\ServerRequestInterface;


class UserController extends Controller{
	
	private $modelUser; 


	public function __construct(ServerRequestInterface $request){
		$this->modelUser= new UserModel();
		parent::__construct($request);
	}


	/**
	 *@return page connexion
	 */
	public function showConnexion(){
		if (isset($_SESSION['access'])){
			if (($_SESSION['access']['auth'])==='valide'){
				return header('Location:/admin/dashboard');
			}	
		}
		$this->show("connexionPage");
	}

	/**
	 *@return page inscritpion in admin area
	 */
	public function showInscription(){ 
		$this->show("inscriptionPage");
	}

	/**
	 *@return page settings in admin area
	 */
	public function showSettings($results=[]){	
		$this->show('settings', $results);
	}


/*this function is not use
	public function showPage(){
		$parts = explode("::", __METHOD__);
		$page = $parts[1]; 
		$this->show($page."Page");
	}*/


	/**
	 *@return page dashboard in admin area
	 */
	public function showDashboardAdmin($results=[]){
		$results['users']=$this->modelUser->all(); 
		$this->show('users', $results); 
	}

	/**
	 *@return page password
	 */
	public function showLost(){
		$this->show('password');
	}




	/**
	 *Verify if user can access restricted area
	 *Put user informations in $_SESSION
	 *@return show function
	 */
	public function connexion(){ 
		$this->modelUser->hydrate($this->request->getParsedBody());
		 
		if (!$this->modelUser->connexion()){
			$_SESSION['success'][2]= "Le couple identifiant/mot de passe est incorrect"; 
			return header("location:/admin");
		};

		$_SESSION['user']=$this->modelUser->one('login', $this->modelUser->login());  
		
		if ($_SESSION['user']['activate'] != 1){
			$_SESSION['success'][2]= "Le compte est désactivé. Merci de contacter l'administrateur du site"; 
			return header("location:/admin");
		}
		
		$_SESSION['access']=['auth'=>'valide', 'login'=>$this->modelUser->login()];
		$_SESSION['success'][1]= "Vous êtes connecté"; 
		return header('location: admin/dashboard');
	}


	/**
	 *Send a new passeword to the user
	 *@return page
	 */
	public function lostPassword(){
		$inputs=$this->modelUser->hydrate($this->request->getParsedBody());
	
		if ($this->modelUser->lostPassword() == false){
			$_SESSION['success'][2]= "Login ou adresse email invalide"; 
			return header("location:/password"); 
		}
		$newPassword = $this->modelUser->generatePassword(); 
		$modelEmail = $this->factory->getModel('Email'); 
		$modelEmail->preparePassword($newPassword, $inputs); 
		return header('location:/admin'); 
	}

	/**
	 *destroy the user session
	 *@return admin page
	 */
	public function logout(){
		session_destroy();
		sessionController::getSession(); 
		$_SESSION['success'][1]= "La session est bien déconnectée"; 
		header('Location:/admin');
	}


	/**
	 *create a new user after verification of the data. 
	 *@return a success or error page. 
	 */
	public function inscription(){ 
		$inputs= $this->modelUser->hydrate($this->request->getParsedBody());
		if ($this->modelUser->prepareInscription($inputs)== false){
			 $_SESSION['success'][2]="L'inscription a échoué !";
			return $this->show("inscriptionPage", $this->modelUser->saveInputs());
		}
		$_SESSION['success'][1]="L'inscription est effectuée !";
		return header('Location:/admin/users');
		
				
	}

	/**
	 *Desactivate an editor and close the session
	 *@return success or failed page
	 */
	public function desactivate(){ 
		if ($this->update('desactivate') == false){
			$_SESSION['success'][2]="Erreur lors de la désactivation. Merci de contacter l'administrateur du site.";
			return header('location:/admin/settings');
		}
		unset ($_SESSION['access']);
		unset ($_SESSION['user']);
		$_SESSION['success'][1]="La désactivation du compte a bien été prise en compte. Pour le réactiver ou le supprimer, merci de contacter l'administrateur du site. "; 
		return header('location:/admin');
		}
		
		
	

/**
*redirection according to inputs in $_POST
*/
///////////A modifier ???????????????????
/*	public function settings(){ 
		$input['id'] = $_SESSION['user']['id']; 
		$inputs = $this->modelUser->hydrate($input);
		$inputs = $inputs + $this->modelUser->hydrate($_POST);  

		if (array_key_exists("email", $inputs)){
			$this->update('email', "settings");
			return $this->showSettings($results); 
		}; 
		if (array_key_exists("password", $inputs)) {
			$this->update('password', "settings");
			return $this->showSettings($results); 
		};
		header('Location:/admin/settings');
		
	}*/


	/**
	 *Update email 
	 *@return success or failed page
	 */
	public function emailUpdate(){
		if($this->update("updateEmail") == false){
			$_SESSION['success'][2]= "La modification de l'email a échoué"; 			
			return $this->showSettings($this->modelUser->saveInputs());
		}
		$_SESSION['success'][1]="La modification a bien été prise en compte"; 
		header("location:/admin/settings"); 
	}
	

	/**
	 *Update password 
	 *@return success or failed page
	 */
	public function passwordUpdate(){
		if($this->update("updatePassword") == false){
			$_SESSION['success'][2]= "La modification du mot de passe a échoué"; 			
			return $this->showSettings($this->modelUser->errors());
		}
		$_SESSION['success'][1]="Le mot de passe a bien été modifié"; 
		header("location:/admin/settings"); 
	}

	/**
	 *Update users 
	 *@return success or failed page
	 */
	public function dashboardAdmin(){  
		if($this->update("updateAccount") == false){
			$_SESSION['success'][2]= "La modification de l'utilisateur a échoué"; 			
			return $this->showDashboardAdmin($this->modelUser->errors());
		}
		$_SESSION['success'][1]="La modification a bien été effectuée"; 
		header("location:/admin/users"); 
	}


	/**
	 *inputs purification and start the method
	 *@param str $method
	 *@return bool
	 */
	private function update($method){
		$input = $this->request->getParsedBody();
		if (!isset($input['id'])){
			$input['id']=$_SESSION['user']['id'];  
		};
		$this->modelUser->hydrate($input); 
		return $this->modelUser->$method();
	}


	/*private function update($label, $page){
		$method = "update".(ucfirst($label)); 
		$results = $this->modelUser->$method($label);  
		
		if( $results !== null){
			$_SESSION['success'][2]= "La modification a échouée"; 
			return $results;
		}
		$_SESSION['success'][1]="La modification a bien été prise en compte"; 
		//$this->editorAccess(); 
		return header("location:/admin/$page");
		
	}*/

	


}