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
	 *Validation of inputs
	 *@param array $inputs
	 *@return bool
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
			$this->saveInputs = $this->isErrors($inputs); 
			return false; 
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
	 *Validate inputs and launch the update function 
	 *@return bool
	 */
	public function updatePassword(){ 
		$fields["password"]= $this->password; 
		$validationInput['password']["password"]=$this->passwordDecrypt; 	
 
		$this->oldPasswordConfirm($this->id); 
		$this->validation($validationInput, 'getValidatorUpdate');
		if ($this->errors != null){
			return false; 
		}
		return $this->recordValid('update', $fields); 
	}

	/**
	 *Validate inputs and start emailUpdate function
	 *@return bool
	 */
	public function updateEmail(){ 
		$fields['email']=$this->email;
		$validationInput['email']=$fields; 
		
		if ($this->one('email', $this->email)){
			array_push($this->errors, "Le champ 'email' existe déjà"); 
		}
		$this->validationUpdate($fields, $validationInput); 
		if ($this->errors != null){
			return false; 
		}
		return $this->recordValid('update', $fields); 
	} 
	

	/**
	 *Validate inputs and start updateAccount function
	 *@return bool
	 */
	public function updateAccount(){
		$fields['is_admin']=$this->isAdmin; 
		$fields['activate']=$this->activate; 
		$validationInput['isBool']=$fields; 
		$this->validationUpdate($fields, $validationInput);
		if ($this->errors != null){
			return false; 
		}
		return $this->recordValid('update', $fields); 
	}


	/**
	 *connexion
	 *@return bool
	 */
	public function connexion(){ 
		if (!password_verify($this->password, $this->one('login', $this->login)['password'])){
			return false;  
		}
		return true; 
		
	}

	/**
	*Get user's informations
	*@param str $login
	*@return array $access
	*/
	public function access($login){
		$result = $this->one('login', $login);
		$access = [
			'id'=>$result['id'],
			'login'=>$result['login'], 
			'name'=>$result['name'], 
			'email'=>$result['email'], 
			'is_admin'=>$result['is_admin'], 
			'activate'=>$result['activate']]; 
		return $access; 

	}

	/**
	 *Desactivate user
	 *@return bool
	 */
	public function desactivate(){
		$update['activate']= $this->activate; 
		return $this->update($update, $this->id); 
	}


	/**
	 *Verify if user have email and login
	 *@return bool
	 */
	public function lostPassword(){
		$result = $this->one('login', $this->login); 
		if ($result == false){
			return false; 
		}
		if($result['email'] != $this->email){
			return false; 
		}
		$this->id = $result['id'];
		return true; 	
	}

	/**
	 *Generate a new password
	 *@return str $hex
	 */
	public function generatePassword(){
		$hex = bin2hex(random_bytes(12)); 
		$pass = password_hash($hex, PASSWORD_DEFAULT, ['cost'=>12]);
		$field['password'] = $pass; 
		$this->update($field, $this->id); 
		return $hex; 
	}

	/**
	 *Launch validator and save inputs if errors
	 *@param array $inputs
	 *@param array validationInputs
	 *@return void
	 */
	private function validationUpdate($inputs, $validationInput){   
		$this->validation($validationInput, 'getValidatorUpdate'); 
		if (!empty($this->errors)){
			$this->saveInputs = $this->isErrors($inputs); 
		}
	}

	
	/**
	 *Verify if password is correct
	 *@return void
	 */
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
///////////////////////////utilisé ?????????
	/*public function dataInBdd(){
		return $this->dataInBdd; 
	}*/



	/**
	 *Validator verification
	 *@return object
	 */
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