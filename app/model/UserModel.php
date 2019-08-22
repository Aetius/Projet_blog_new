<?php
namespace App\model;
use App\model\BDDConnection;
use PDO;
use App\controller\UsersController;
use App\model\AbstractModel;
use App\utilities\Verification;

class UserModel{

	public $error=[];
	private $name;
	private $lastname;
	private $mail;
	private $login ;
	private $password; 
	private $passwordConnexion;
	private $connexion;
	private $bdd;
	private $dataInBdd;

	public function __construct(){
		$this->bdd=(BDDConnection::Connection()); 
	}


/*private functions seaching datas in the bdd */
	private function create(){		
		$request=$this->bdd->prepare('INSERT INTO users(name, last_name, mail, login, password, role) VALUES(:name, :last_name, :mail, :login, :password, :role)');
		$request->execute(array(
			'name'=>($this->name), 
			'last_name'=>($this->lastName),
			'mail'=>($this->mail),
			'login'=>($this->login),
			'password'=>($this->password),
			'role'=>("editor")
			));
		$request->closeCursor();
	}

	private function readUser($login){
		$request=$this->bdd->prepare("SELECT * FROM users WHERE login =:login");
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
		return 'true';
	}

	private function loginPassword($value){
		$request=$this->bdd->prepare("SELECT password FROM users WHERE login=:login");
		$request->execute(array( ':login'=>$value ));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result['password'];
	}


	
/*functions calling the request, and return the result of this action .*/
	public function inscription(){
		if (!empty($this->name)&&!empty($this->lastName)&&!empty($this->mail)&&!empty($this->login)&&!empty($this->password)){
			gettype($this->password);
			$this->create();
			return true;
		}else{
			return false;
		}
	}

	public function connexion(){
		$login = $this->login; 
		if (password_verify($this->password, $this->loginPassword($login))){
			return true;  
		}
	}

	public function user($login){
		return $this->readUser($login);
	}

	public function saveInput(){
		return $saveInput=["Name"=>"$this->name", "Lastname"=>$this->lastname, "Mail"=>$this->mail, "Login"=>$this->login];
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
			return $this->lastName = $value;
		}else{
			return $this->error[$field]='Le nom est invalide';
		}
	}

	public function setMail($value, $field){
		if ((!isset($value))||(Verification::mail($value) != 'valide')){
			return $this->error[$field]="L'email est invalide";
		}else{
			$this->mail = $value; 
			$this->isInBDD($value, 'mail', 'mailExist');
		}
	}

	public function setLogin($value, $field){
		if(!(is_string($value))) {
			return $this->error[$field]="Le login est invalide";	
		}else{
			$this->login = $value;
			$this->isInBDD($value, 'login', 'loginExist'); 
		}
	}

	public function setPassword($value, $field){  ///////////////////////::ne pas oublier de supprimer $this->password = $value; (permet d'entrer des mdp de moins de 8 caractères)/////////////////
		$this->password = $value;
		if (Verification::password($value) == 'valide'){
			return $this->password = $value; 
		}else{
			return $this->error[$field]='Le mot de passe est invalide';
		}
	}

	public function setPasswordConfirm($value, $field){
		if (Verification::password($value) == 'valide'){
			var_dump($value);
			var_dump($this->password);
			die();
			if ($this->password === $value){
				return ($this->password = password_hash($value, PASSWORD_DEFAULT, ['cost'=>12]));
			}
		}else{
			return $this->error[$field]="La confirmation du mot de passe est invalide";
		}
	}

/*getters*/
	public function login(){
		return $this->login;
	}

	/*function that verify if the data is in the bdd. works with setters*/
	private function isInBDD($value, $field, $key){
		if(($this->verifUse($value, $field))==true){
			return $this->dataInBdd[$key]=true;  
		}
	}
	



}