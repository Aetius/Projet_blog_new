<?php
namespace App\model;

//use PDO;
//use App\controller\UsersController;
use App\model\AppModel;
use App\utilities\Validator;


class UserModel extends AppModel{

	
	private $name;
	private $lastname;
	private $email;
	private $login ;
	private $password; 
	private $passwordConnexion;
	private $connexion;
	private $dataBdd;
	private $oldPassword;
	private $passwordDecrypt;
	private $id; 
	private $activate; 


/*	public function __construct(){
		parent::__construct(); 
		$this->bdd=(BDDConnection::Connection()); 
	}*/





/*private functions seaching datas in the bdd */
/*	public function create(){		
		
		$request=$this->bdd->prepare('INSERT INTO users(name, last_name, email, login, password, role) VALUES(:name, :last_name, :email, :login, :password, :role)');
		$request->execute(array(
			'name'=>($this->name), 
			'last_name'=>($this->lastname),
			'email'=>($this->email),
			'login'=>($this->login),
			'password'=>($this->password),
			'role'=>("editor")
			));
		$request->closeCursor();
	}*/

/*	public function readUser($login){
		
		$request=$this->bdd->prepare("SELECT name, email, role FROM users WHERE login =:login");
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
			'email'=>($this->email),
			'login'=>($this->login),
			'password'=>($this->password),
			'role'=>("editor")
		);
		return $this->creationSuccess('create', $fields);
	}


	public function inscription($inputs){
		$this->validation($inputs, 'validatorInscription'); 
		
		if ($this->verifUse($this->email, 'email')){
			$this->errors[]="Cette adresse email existe déjà";
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
		}else{

			return false; 
		}
	}



	public function saveInput(){
		return $saveInput=["name"=>$this->name, "lastname"=>$this->lastname, "email"=>$this->email, "login"=>$this->login];
	}


	public function desactivate(){
		$update['activate']= $this->activate; 
		$results = $this->update($update, $this->id); 
		return $results; 
	}

	public function updatePassword($id, $label){ 
		if ($this->oldPasswordConfirm($id)!==true){
			return $this->errors; 
		}else{
			$fields[$label]= $this->passwordDecrypt;
			return $this->successUpdate($id, $label, $fields); 
		}
	}

	public function updateEmail($id, $label){ 
		if ($this->verifUse($label, $this->$label)){
			array_push($this->errors, "Le champ $label existe déjà"); 
		}
		$fields[$label]=$this->email;
		return $this->successUpdate($id, $label, $fields);  
	}



	private function successUpdate($id, $label, $fields){ 
		$this->validation($fields, 'validatorUpdate'); 

		$update[$label]=$this->$label; 
		if(!$this->errors){
			return($this->update($update, $id));
		}else{
			return $this->errors; 
		}

	}

	


	private function oldPasswordConfirm($id){ 
		if (!password_verify($this->oldPassword, $this->one('id', $id)['password'])){
			$this->errors[]="L'ancien mot de passe n'est pas valide";
		}else{
			return true; 
		}
	
	}
		
	





	/*setters*/

	public function setId($value){
		return $this->id = $value; 
	}

	public function setName($value){
		return $this->name = $value;
	}

	public function setLastName($value){
		return $this->lastname = $value;
	}

	public function setEmail($value){
		return $this->email = $value;
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
				$this->passwordDecrypt = $value; 
		}else{
			return $this->errors[]="La confirmation du mot de passe est invalide";
		}
		
	}

	public function setActivate($value){
		return $this->activate = $value; 
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






	/*protected function getValidator($inputs, $key, $value){
			return (new Validator($inputs))
				->$key($value); 
		
	}*/

	protected function validatorUpdate($inputs){
		$key = array_key_first($inputs);
		return(new Validator($inputs))
			->$key($key);
	}

	

	protected function validatorInscription($inputs){
		$fieldsExist= ['name', 'lastname', 'email', 'login', 'password', 'passwordConfirm']; 
		return(new Validator($inputs))
			->name('name')
			->name('lastname')
			->email('email')
			->login('login')
			->password('password')
			->password('passwordConfirm')
			->length('password', 8)
			->notEmpty($fieldsExist);
	}




}