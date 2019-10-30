<?php
namespace App\model;


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
	private $activate; 
	private $isAdmin; 


/**
*functions prepare before requests
*/
	public function prepareInscription($inputs){
		$this->validation($inputs); 
		
		if ($this->one( 'email', $this->email)){
			$this->errors[]="Cette adresse email existe déjà";
		};
		if ($this->one('login', $this->login)){
			$this->errors[]="Ce login existe déjà";
		};
		
		if (!empty($this->errors)){
			return $this->isErrors($inputs); 
		};
		$fields=array(
			'name'=>($this->name), 
			'last_name'=>($this->lastname),
			'email'=>($this->email),
			'login'=>($this->login),
			'password'=>($this->password),
			'is_admin'=>("0")
		);
		return $this->recordValid('create', $fields);
	}

/**
*validation of datas before send to update
*/
	public function updatePassword($label){ 
		$this->oldPasswordConfirm($this->id); 
		$fields[$label]= $this->passwordDecrypt;
		$validationInput['password']=$fields; 	

		return $this->validationUpdate($fields, $validationInput); 
	}

	public function updateEmail($label){ 
		$fields['email']=$this->email;
		$validationInput['email']=$fields; 
		if ($this->one($label, $this->$label)){
			array_push($this->errors, "Le champ $label existe déjà"); 
		}
		return $this->validationUpdate($fields, $validationInput);  
	}


	public function updateAccount($label){
		$fields['is_admin']=$this->isAdmin; 
		$fields['activate']=$this->activate; 
		$validationInput['isBool']=$fields; 
		return $this->validationUpdate($fields, $validationInput);
	}


	


	public function connexion(){ 
		if (password_verify($this->password, $this->one('login', $this->login)['password'])){
			return true;  
		}else{
			return false; 
		}
	}


	public function desactivate(){
		$update['activate']= $this->activate; 
		$results = $this->update($update, $this->id); 
		return $results; 
	}


	public function lostPassword(){
		$result = $this->one('login', $this->login); 
		if ($result != false){
			if($result['email'] == $this->email){
				$newPassword = $this->generatePassword($result); 
				return $newPassword; 
			}
		}
		return false; 
	}

/**
*valide if there's an error, and return if update is done 
*return a bool
*/
	private function validationUpdate($inputs, $validationInput){  
		$this->validation($validationInput, 'getValidatorUpdate'); 
		if (!empty($this->errors)){
			return $this->isErrors($inputs); 
		}
		return($this->recordValid('update', $inputs));
	}


	private function generatePassword($result){
		$hex = bin2hex(random_bytes(12)); 
		$pass = password_hash($hex, PASSWORD_DEFAULT, ['cost'=>12]);
		$field['password'] = $pass; 
		$this->update($field, $result['id']); 
		return $hex; 
	}


	private function oldPasswordConfirm($id){ 
		if (!password_verify($this->oldPassword, $this->one('id', $id)['password'])){
			$this->errors[]="L'ancien mot de passe n'est pas valide";
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

	public function setIs_admin($value){
		return $this->isAdmin = $value; 
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