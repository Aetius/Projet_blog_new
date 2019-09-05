<?php
namespace App\model;
use App\model\BDDConnection;
use PDO;
use App\controller\UsersController;
use App\model\AbstractModel;
use App\utilities\Purifier;
use App\utilities\ErrorException;

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
	public function create(){		
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

	public function readUser($login){
		$request=$this->bdd->prepare("SELECT name, mail, role FROM users WHERE login =:login");
		$request->execute(array(':login'=> $login));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}


	public function verifUse($value, $field){
		
		$request=$this->bdd->prepare("SELECT id FROM users WHERE $field=:field");
		$request->execute(array(':field' => $value));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result;
	}

	public function loginPassword($value){
		$request=$this->bdd->prepare("SELECT password FROM users WHERE login=:login");
		$request->execute(array( ':login'=>$value ));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result['password'];
	}

	public function modifData($login, $modif, $newValue){
		$request=$this->bdd->prepare ("UPDATE users SET $modif=:valueModif WHERE login=:login");
		$result=$request->execute(array(
			':login'=>$login,
			':valueModif'=>$newValue));
		$request->closeCursor(); 
		return $result;
	}

	public function delete($login){
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
		try {
			if ((password_verify($this->oldPassword, $this->loginPassword($login)))!=true){
				throw new ErrorException("L'ancien mot de passe n'est pas valide");
			}
		}catch (ErrorException $exception){
			$this->error['password']= ($exception); 
		}
	}


	public function saveInput(){
		return $saveInput=["Name"=>$this->name, "Lastname"=>$this->lastname, "Mail"=>$this->mail, "Login"=>$this->login];
	}

	public function update($login, $label){
		return($this->modifData($login, $label, $this->$label));
	}




	/*setters*/

	public function setName($value, $field){
		try {
			if (Purifier::name($value) != 'valide'){
				throw new ErrorException('Le prénom est invalide');
			}$this->name = $value;
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}

		/*if (Purifier::name($value) === true){
			return $this->name = $value;
		}else {
			return $this->error[$field]='Le prénom est invalide';
		}*/
	}

	public function setLastName($value, $field){
		try {
			if (Purifier::name($value) != 'valide'){
				throw new ErrorException('Le nom est invalide');
			}
			$this->lastname = $value;
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}

		/*if (Purifier::name($value) === true){
			return $this->lastname = $value;
		}else{
			return $this->error[$field]='Le nom est invalide';
		}*/
	}

	public function setMail($value, $field){
		try{
			if ((!isset($value))||(Purifier::mail($value) != 'valide')){
				throw new ErrorException("L'email est invalide");
			}
			$this->mail = $value; 
			$this->isInBDD($value, 'mail', 'Mail');
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}



		/*if ((!isset($value))||(Purifier::mail($value) === true)){
			return $this->error[$field]="L'email est invalide";
		}else{
			$this->mail = $value; 
			$this->isInBDD($value, 'mail', 'Mail');
		}*/
	}

	public function setLogin($value, $field){
		try {
			if (is_int($value)){
				throw new ErrorException("Le login est invalide");
			}
			$this->login = $value;
			$this->isInBDD($value, 'login', 'Login'); 
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}


		/*if(!(is_string($value))) {
			return $this->error[$field]="Le login est invalide";	
		}else{
			$this->login = $value;
			$this->isInBDD($value, 'login', 'Login'); 
		}*/
	}

	public function setPassword($value, $field){  
		try {
			if (Purifier::password($value) != 'valide'){
				throw new ErrorException("Le mot de passe est invalide");
			}
			$this->password = $value;
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}

		/*if (Purifier::password($value) === true){
			return $this->password = $value; 
		}else{
			return $this->error[$field]='Le mot de passe est invalide';
		}*/
	}

	public function setoldPassword($value, $field){
		try {
			if (Purifier::password($value) != 'valide'){
				throw new ErrorException("Le mot de passe est invalide");
			}
			$this->oldPassword = $value;
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}

		/*if (Purifier::password($value) === true){
			return $this->oldPassword = $value; 
		}else{
			return $this->error[$field]='Le mot de passe est invalide';
		}*/
	}

	public function setPasswordConfirm($value, $field){

		try {
			if ((Purifier::password($value) != 'valide') || ($this->password != $value)){
				throw new ErrorException("La confirmation du mot de passe est invalide");
			}
			$this->password = password_hash($value, PASSWORD_DEFAULT, ['cost'=>12]);
		}catch (ErrorException $exception){
			$this->error[$field]= ($exception);
		}	


		/*if (Purifier::password($value) === true){
			if ($this->password === $value){
				$this->password = password_hash($value, PASSWORD_DEFAULT, ['cost'=>12]);
			}else{
				return $this->error[$field]="La confirmation du mot de passe est invalide";
			}
		}*/
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
	


/*hydrate user in UserModel.  */	
	public function hydrateUser(){
		$allKey=[];
		foreach ($_POST as $key => $value) {
			$key = Purifier::input($key);
			$value = Purifier::input($value);  
			$name = "set".$key;
			array_push($allKey, $key);
			if (method_exists($this,$name)){ 
				$this->$name($value, $key); 
			}
		}
		return $allKey;
	}

/*verify if mail or login are already in bdd*/
	public function duplication(){
		if (!empty($this->dataInBdd)){
				
			foreach ($this->dataInBdd as $key => $value) {
				if ($key ==="Login") {
					$this->error['Login']="Le login est déjà utilisée";
				}elseif ($key === "Mail"){
					$this->error['Mail']="L'adresse mail est déjà utilisée";
				}
			}
		}
	}



}