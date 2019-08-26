<?php
namespace App\model;
use App\model\BDDConnection;
use PDO;
use App\controller\UsersController;
use App\model\AbstractModel;
use App\utilities\Verification;

class UserModel{

	private $error=[];
	private $name;
	private $lastname;
	private $mail;
	private $login ;
	private $password; 
	private $passwordConnexion;
	private $connexion;
	private $bdd;
	private $dataInBdd;
	private $oldPassword;


	public function __construct(){
		$this->bdd=(BDDConnection::Connection()); 
	}


/*private functions seaching datas in the bdd */
	private function create(){		
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
	}

	private function readUser($login){
		$request=$this->bdd->prepare("SELECT name, mail, role FROM users WHERE login =:login");
		$request->execute(array(':login'=> $login));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}


	private function verifUse($value, $field){
		
		$request=$this->bdd->prepare("SELECT id FROM users WHERE $field=:field");
		$request->execute(array(':field' => $value));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}

	private function loginPassword($value){
		$request=$this->bdd->prepare("SELECT password FROM users WHERE login=:login");
		$request->execute(array( ':login'=>$value ));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result['password'];
	}

	private function modifData($login, $modif, $newValue){
		$request=$this->bdd->prepare ("UPDATE users SET $modif=:valueModif WHERE login=:login");
		$result=$request->execute(array(
			':login'=>$login,
			'valueModif'=>$newValue));
		$request->closeCursor(); 
		return $result;
	}

	private function deleteUser($login){
		$request=$this->bdd->prepare("DELETE FROM users WHERE login=:login");
		$result=$request->execute(array(":login"=>$login));
		$request->closeCursor(); 
		return $result;
	}


	
/*functions calling the request, and return the result of this action .*/
	public function inscription(){
		if (!empty($this->name)&&!empty($this->lastname)&&!empty($this->mail)&&!empty($this->login)&&!empty($this->password)){
				$this->create();
				return true;
		}else{
			return false;
		}
	}

	public function connexion(){
		if (password_verify($this->password, $this->loginPassword($this->login))){
			return true;  
		}
	}

	public function oldPasswordConfirm($login){
		if (password_verify($this->oldPassword, $this->loginPassword($login))){
			return true;  
		}
	}

	public function user($login){
		return $this->readUser($login);
	}

	public function saveInput(){
		return $saveInput=["Name"=>$this->name, "Lastname"=>$this->lastname, "Mail"=>$this->mail, "Login"=>$this->login];
	}

	public function update($label, $login){
		return($this->modifData($login, $label, $this->$label));
	}

	public function delete($login){
		return($this->deleteUser($login));
	}



	/*setters*/

	public function setName($value, $field){
		if (Verification::name($value) == 'valide'){
			return $this->name = $value;
		}else {
			return $this->error[$field]='Le prénom est invalide';
		}
	}

	public function setLastName($value, $field){
		if (Verification::name($value) == 'valide'){
			return $this->lastname = $value;
		}else{
			return $this->error[$field]='Le nom est invalide';
		}
	}

	public function setMail($value, $field){
		if ((!isset($value))||(Verification::mail($value) != 'valide')){
			return $this->error[$field]="L'email est invalide";
		}else{
			$this->mail = $value; 
			$this->isInBDD($value, 'mail', 'Mail');
		}
	}

	public function setLogin($value, $field){
		if(!(is_string($value))) {
			return $this->error[$field]="Le login est invalide";	
		}else{
			$this->login = $value;
			$this->isInBDD($value, 'login', 'Login'); 
		}
	}

	public function setPassword($value, $field){  
		if (Verification::password($value) == 'valide'){
			return $this->password = $value; 
		}else{
			return $this->error[$field]='Le mot de passe est invalide';
		}
	}

	public function setoldPassword($value, $field){
		if (Verification::password($value) == 'valide'){
			return $this->oldPassword = $value; 
		}else{
			return $this->error[$field]='Le mot de passe est invalide';
		}
	}

	public function setPasswordConfirm($value, $field){
		if (Verification::password($value) == 'valide'){
			if ($this->password === $value){
				$this->password = password_hash($value, PASSWORD_DEFAULT, ['cost'=>12]);
			}else{
				return $this->error[$field]="La confirmation du mot de passe est invalide";
			}
		}
	}


			/*getters*/
	public function login(){
		return $this->login;
	}

	public function dataInBdd(){
		return $this->dataInBdd; 
	}

	public function error(){
		return $this->error; 
	}



			/*function that verify if the data is in the bdd. works with setters*/
	private function isInBDD($value, $field, $key){
		if(($this->verifUse($value, $field))==true){
			return $this->dataInBdd[$key]=true;  
		}
	}
	



}