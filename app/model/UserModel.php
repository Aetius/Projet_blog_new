<?php
namespace App\model;

use PDO;
use App\controller\UsersController;
use App\model\AppModel;
use App\utilities\Validator;


class UserModel extends AppModel{

	
	private $name;
	private $lastname;
	private $mail;
	private $login ;
	private $password; 
	private $passwordConnexion;
	private $connexion;
	private $dataBdd;
	private $oldPassword;


/*	public function __construct(){
		parent::__construct(); 
		$this->bdd=(BDDConnection::Connection()); 
	}*/





/*private functions seaching datas in the bdd */
/*	public function create(){		
		
		$request=$this->bdd->prepare('INSERT INTO users(name, last_name, mail, login, password, role) VALUES(:name, :last_name, :mail, :login, :password, :role)');
		$request->execute(array(
			'name'=>($this->name), 
			'last_name'=>($this->lastname),
			'mail'=>($this->mail),
			'login'=>($this->login),
			'password'=>($this->password),
			'role'=>("editor")
			));
		$request->closeCursor();
	}*/

/*	public function readUser($login){
		
		$request=$this->bdd->prepare("SELECT name, mail, role FROM users WHERE login =:login");
		$request->execute(array(':login'=> $login));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}*/

/*
	public function verifUse($value, $field){
		
		$request=$this->bdd->prepare("SELECT id FROM users WHERE $field=:field");
		$request->execute(array(':field' => $value));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}*/

/*	public function loginPassword($value){
		
		$request=$this->bdd->prepare("SELECT password FROM users WHERE login=:login");
		$request->execute(array( ':login'=>$value ));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result['password'];
	}*/

/*	public function modifData($login, $modif, $newValue){
		
		$request=$this->bdd->prepare ("UPDATE users SET $modif=:valueModif WHERE login=:login");
		$result=$request->execute(array(
			':login'=>$login,
			':valueModif'=>$newValue));
		$request->closeCursor(); 
		return $result;
	}*/

/*	public function delete($login){
		
		$request=$this->bdd->prepare("DELETE FROM users WHERE login=:login");
		$result=$request->execute(array(":login"=>$login));
		$request->closeCursor(); 
		return $result;
	}*/


	
/*functions calling the request, and return the result of this action .*/

	public function createUser(){
		$fields=array(
			'name'=>($this->name), 
			'last_name'=>($this->lastname),
			'email'=>($this->mail),
			'login'=>($this->login),
			'password'=>($this->password),
			'role'=>("editor")
		);
		return $this->creationSuccess('create', $fields);
	}

	public function inscription($inputs){
		$this->validation($inputs); 
		
		if ($this->verifUse($this->mail, 'mail')){
			$this->errors[]="Cette adresse mail existe déjà";
		};
		if ($this->verifUse($this->login, 'login')){
			$this->errors[]="Ce login existe déjà";
		};
		if (!$this->errors){
			return $this->createUser();
		}
	
	}

	public function connexion(){
		if (password_verify($this->password, $this->one('login', $this->login)['password'])){
			return true;  
		}
	}

	public function oldPasswordConfirm($login){
		try {
			if ((password_verify($this->oldPassword, $this->one('login', $login)['password']))!=true){
				throw new ErrorException("L'ancien mot de passe n'est pas valide");
			}
		}catch (ErrorException $exception){
			$this->errors['password']= ($exception); 
		}
	}


	public function saveInput(){
		return $saveInput=["Name"=>$this->name, "Lastname"=>$this->lastname, "Mail"=>$this->mail, "Login"=>$this->login];
	}

	public function updateUser($id, $label){
		$fields[$label]=$this->label;  
		return($this->update($fields, $id));
	}





	/*setters*/

	public function setName($value){
		return $this->name = $value;
	}

	public function setLastName($value){
		return $this->lastname = $value;
	}

	public function setMail($value){
		return $this->mail = $value;
	}

	public function setLogin($value){
		$this->login = $value;
	}


	public function setPassword($value){  
		return $this->password = $value; 
	}


	public function setoldPassword($value){
		return $this->oldPassword = $value; 
	}

	public function setPasswordConfirm($value){
		if ($this->password === $value){
				$this->password = password_hash($value, PASSWORD_DEFAULT, ['cost'=>12]);
		}else{
			return $this->errors[]="La confirmation du mot de passe est invalide";
		}
		
	}


	
			/*getters*/
	public function login(){
		return $this->login;
	}

	public function dataInBdd(){
		return $this->dataInBdd; 
	}

	public function errors(){
		return $this->errors; 
	}


	protected function getValidator($inputs){
		$fieldsExist= ['Name', 'Lastname', 'Mail', 'Login', 'Password', 'PasswordConfirm']; 
		return(new Validator($inputs))
			->name('Name')
			->name('Lastname')
			->mail('Mail')
			->password('Password')
			->password('PasswordConfirm')
			->length('Password', 8)
			->notEmpty($fieldsExist);
	}




}