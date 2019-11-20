<?php
namespace App\Controller;

use App\Model\UserModel;
use App\Utilities\Session;
use Psr\Http\Message\ServerRequestInterface;


class UserController extends Controller{
	
	private $modelUser; 


	public function __construct(ServerRequestInterface $request){
		$this->modelUser= new UserModel();
		parent::__construct($request);
	}


    /**
     * @return array
     */
    public function showConnexion(){
		if (isset($_SESSION['access'])){
			if (($_SESSION['access']['auth'])==='valide'){
			    return $this->redirectTo('/admin/dashboard');

			}	
		}
      return $this->show("connexionPage");


	}

    /**
     * @return array
     */
    public function showInscription(){
		return $this->show("inscriptionPage");
	}

    /**
     * @param array $results
     * @return array
     */
	public function showSettings($results=[]){
		 return $this->show('settings', $results);

	}

    /**
     * @param array $results
     * @return array
     */
	public function showDashboardAdmin($results=[]){
		$results['users']=$this->modelUser->all(); 
		return $this->show('users', $results);
	}

    /**
     * @return array
     */
	public function showLost(){
		return $this->show('password');
	}


	/**
	 *Verify if user can access restricted area
	 *Put user informations in $_SESSION
     * @return array
	 */
	public function connexion(){ 
		$this->modelUser->hydrate($this->request->getParsedBody());
		 
		if (!$this->modelUser->connexion()){
			$_SESSION['success'][2]= "Le couple identifiant/mot de passe est incorrect"; 
			 return $this->redirectTo("/admin");
		};

		$_SESSION['user']=$this->modelUser->one('login', $this->modelUser->login());  
		
		if ($_SESSION['user']['activate'] != 1){
			$_SESSION['success'][2]= "Le compte est désactivé. Merci de contacter l'administrateur du site"; 
			 return $this->redirectTo("/admin");
		}
		
		$_SESSION['access']=['auth'=>'valide', 'login'=>$this->modelUser->login()];
		$_SESSION['success'][1]= "Vous êtes connecté"; 
		 return $this->redirectTo(' admin/dashboard');
	}


	/**
	 *Send a new passeword to the user
     * @return array
	 */
	public function lostPassword(){
		$inputs=$this->modelUser->hydrate($this->request->getParsedBody());
	
		if ($this->modelUser->lostPassword() == false){
			$_SESSION['success'][2]= "Login ou adresse email invalide"; 
			return $this->redirectTo("/password");
		}
		$newPassword = $this->modelUser->generatePassword(); 
		$modelEmail = $this->factory->getModel('Email'); 
		$modelEmail->preparePassword($newPassword, $inputs); 
		return $this->redirectTo('/admin');
	}

	/**
	 *destroy the user session
     * @return array
	 */
	public function logout(){
		session_destroy();
		Session::getSession();
		$_SESSION['success'][1]= "La session est bien déconnectée"; 
		return $this->redirectTo('/admin');
	}


	/**
	 *create a new user after verification of the data.
     * @return array|void
	 */
	public function inscription(){ 
		$inputs= $this->modelUser->hydrate($this->request->getParsedBody());
		if ($this->modelUser->prepareInscription($inputs)== false){
			 $_SESSION['success'][2]="L'inscription a échoué !";
			 return $this->show("inscriptionPage", $this->modelUser->saveInputs());
		}
		$_SESSION['success'][1]="L'inscription est effectuée !";
		return $this->redirectTo('/admin/users');
	}

	/**
	 *Desactivate an editor and close the session
     * @return array
	 */
	public function desactivate(){ 
		if ($this->update('desactivate') == false){
			$_SESSION['success'][2]="Erreur lors de la désactivation. Merci de contacter l'administrateur du site.";
			return $this->redirectTo('/admin/settings');
		}
		unset ($_SESSION['access']);
		unset ($_SESSION['user']);
		$_SESSION['success'][1]="La désactivation du compte a bien été prise en compte. Pour le réactiver ou le supprimer, merci de contacter l'administrateur du site. "; 
		return $this->redirectTo('/admin');
	}


    /**
     * @return array
     */
    public function emailUpdate(){
		if($this->update("updateEmail") == false){
			$_SESSION['success'][2]= "La modification de l'email a échoué"; 			
			return $this->showSettings($this->modelUser->saveInputs());
		}
		$_SESSION['success'][1]="La modification a bien été prise en compte"; 
		return $this->redirectTo("/admin/settings");
	}
	

	/**
	 *Update password
     * @return array
	 */
	public function passwordUpdate(){
		if($this->update("updatePassword") == false){
			$_SESSION['success'][2]= "La modification du mot de passe a échoué"; 			
			return $this->showSettings($this->modelUser->errors());
		}
		$_SESSION['success'][1]="Le mot de passe a bien été modifié"; 
		return $this->redirectTo("/admin/settings");
	}


    /**
     * Update User
     * @return array
     */
    public function dashboardAdmin(){
		if($this->update("updateAccount") == false){
			$_SESSION['success'][2]= "La modification de l'utilisateur a échoué"; 			
			return $this->showDashboardAdmin($this->modelUser->errors());
		}
		$_SESSION['success'][1]="La modification a bien été effectuée"; 
		return $this->redirectTo("/admin/users");
	}


	/**
	 *inputs purification and start the method
	 *@param string $method
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




}