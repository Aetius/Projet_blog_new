<?php
namespace App\model;
use App\model\BDDConnection;
use PDO;
use App\controller\UsersController;
use App\model\AbstractModel;
use App\utilities\Verification;

class UserModel /*extends AbstractModel*/{
		private $_bddUserLogin;
		private $_bddUserPassword;
		private $_bddUserRole;
		private $_read;
		private $_userInBDD;
		private $_verifLogin;
		private $_verifMail;
		private $_bdd; 
		

		public $error;
		private $_name;
		private $_lastName;
		private $_mail;
		private $_login ;
		private $_password; 
		private $__passwordConnexion;
		private $_connexion;
		

	public function __construct(){		
		$this->_bdd=(BDDConnection::Connection()); 
	}

	public function create(){		
		$request=$this->_bdd->prepare('INSERT INTO users(name, last_name, mail, login, password, role) VALUES(:name, :last_name, :mail, :login, :password, :role)');
		$request->execute(array(
			'name'=>($this->_name), 
			'last_name'=>($this->_lastName),
			'mail'=>($this->_mail),
			'login'=>($this->_login),
			'password'=>($this->_password),
			'role'=>("editor")
			));
		$request->closeCursor();
	}

	public function read($userControl){
		
		$request=$this->_bdd->prepare("SELECT login,password, role FROM users WHERE login =:login");
		$request->execute(array('login'=> $userControl->login()));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$this->_bddUserLogin = $result['login'];
		$this->_bddUserPassword=$result['password'];
		$this->_bddUserRole=$result['role'];
	}

	public function verifUse($name, $id){
		$request=$this->_bdd->prepare("SELECT $id, password, role FROM users WHERE $id =:$id");
		$request->execute(array($id=>$name));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$this->_passwordConnexion =$result['password']; 
		$this->_role = $result['role'];
		$request->closeCursor(); 
		return $result[$id];
		
		/*$this->_read=$this->_bdd->prepare("SELECT mail FROM users WHERE mail =:mail");
		$this->_read->execute(array('mail'=> $userControl->mail()));
		$this->_userInBDD=$this->_read->fetch(PDO::FETCH_ASSOC);
		$this->_verifMail = $this->_userInBDD['mail'];
		$this->_read->closeCursor(); */
	}


	public function update(){
	}

	public function delete(){

	}



	////////////setters//////////////

	public function setVerification($verification){
		return Verification::input($verification);
	}

	public function setName($name, $id){
		$name = $this->setVerification($name);
		if(preg_match("#^[a-zA-Z]*$#", $name)){
			return $this->_name = $name;
		}else{
			return $this->error='nom invalide';
		}
	}

	public function setLastname($name, $id){
		$name = $this->setVerification($name);
		if(preg_match("#^[a-zA-Z]*$#", $name)){
			return $this->_lastName = $name;
		}else{
			return $this->error='nom invalide';
		}
	}

	public function setMail($name, $id){
		$name = $this->setVerification($name);
		if (!(preg_match("#^[a-zA-Z0-9\.]*[@]{1}[a-z]{1,20}\.[a-z]{2,8}$#", $name))){
			return $this->error='email invalide';
		} elseif (strcmp($this->verifUse($name, "mail"), $name)==0){
			return $this->error='email déjà utilisé';
		}else{
			return $this->_mail = $name; 
		}
	}

	public function setLogin($name, $id){
		$name = $this->setVerification($name);
		if(!(is_string($name))) {
			return $this->error="login invalide";
		}elseif ((strcmp($this->verifUse($name, "login"), $name)==0)) {
			if ($id == 'connexion'){
				return $this->_login = $name; 
			}else{
				return $this->error="login déjà utilisé";
			}
		}elseif (($id == 'inscription')) {
			return $this->_login = $name; 
		}else {
			return $this->error="login invalide";
		}
	}

	public function setPassword($name, $id){
		if (is_string($name = $this->setVerification($name))){
			if ($id =='connexion'){
				if ((!(is_null($name))) && (password_verify($name, $this->_passwordConnexion))){
					return $this->_connexion = true;
				}else {
					return $this->_connexion = false;
				}
			}elseif ($id =='inscription'){
				return $this->_password = $name;
			}else{
				return $this->_error='mot de passe invalide';
			}
		}
	}

	public function setPasswordConfirm($name, $id){
		($name = $this->setVerification($name));
		if (is_string($name)&& $name == $this->_password){
			return ($this->_password = password_hash($name, PASSWORD_DEFAULT)); 
		}else {
			return $this->error="confirmation du mot de passe invalide";
		}
	}



//////a modifier
	public function setVerifPassword(){
		$var=$_POST['password'];
		$var = Verification::InVerify($var);
		if(is_string($var)){
			$this->_password=$var;
			return $this->_password;
		}else{
			return $this->_error='mot de passe invalide';
		}
	}


///////////////////////////getters//////////////////

	public function role(){
		return $this->_role;
	}

	public function name(){
		return $this->_name;
	}

	public function connexion(){
		return $this->_connexion; 
	}


}