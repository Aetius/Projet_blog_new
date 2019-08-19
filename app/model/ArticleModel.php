<?php

namespace App\Model; 
use App\Model\BDDConnection;
use PDO;
use App\App\controller\ArticleController;




class ArticleModel{
	private $_bdd;
	private $_description;
	private $_dateCreation;
	private $_content;
	private $_id;
	private $_result=[]; 
	private $_published; 
	private $_publishedDate; 
	private $_title; 
	public $error=[]; 


	public function __construct(){		
		$this->_bdd=(BDDConnection::Connection()); 
	}

	public function create(){
		$request=$this->_bdd->prepare('INSERT INTO articles(title, description, content, publicated, date_creation, author_id) VALUES(:title, :description, :content, :publicated, :date_creation, :author_id )');
		$request->execute(array(
			'title'=>($this->_title), 
			'description'=>($this->_description),
			'content'=>($this->_content),
			'publicated'=>($this->_publishedDate),
			'date_creation'=>($this->setDate()),
			'author_id'=>("6")
			));
		$request->closeCursor();
	}

	public function all(){
		$request=$this->_bdd->prepare("SELECT * FROM articles ORDER BY id DESC LIMIT 0,10");
		$request->execute(array());
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			array_push($this->_result, $result);
		}
		$request->closeCursor();
		return $this->_result; 
	}

	public function readOne($id){
		$request=$this->_bdd->prepare("SELECT * FROM articles WHERE id=$id");
		$request->execute(array());
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			array_push($this->_result, $result);
		}
		$request->closeCursor();
		return $this->_result; 
	}

	public function verifUse($name, $id){
		$request=$this->_bdd->prepare("SELECT $id FROM articles WHERE $id=:$id");
		$request->execute(array($id=>$name));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result[$id];
	}
	

	///////////////////getters//////////////

	public function result(){
		return $this->_result; 
	}



	////////////////setters///////////////
	public function setVerification($verification){
		return \App\utilities\Verification::textVerify($verification);
	}

	public function setDate(){
		$datetime = getdate(); 
		$date = $datetime['year']."-".$datetime['mon']."-".($datetime['mday']); 
		return $date;
	}

	public function setTitle($name){
		$name = $this->setVerification($name);
		if (strcmp($this->verifUse($name, 'title'), $name)==0){
			return $this->error ="Cet article existe déjà!";
		}else{
			return $this->_title = $name;
		}
	}

	public function setDescription($name){
		$name = $this->setVerification($name);
		return $this->_description=$name; 
	}

	public function setContent($name){
		$name = $this->setVerification($name);
		return $this->_content=$name; 

	}

	public function setPublished($name){
		$name = $this->setVerification($name);
		if(preg_match("#oui|non#", $name)){
			return $this->_published; 
		}
	}

	public function setPublishedDate($name){
		$name=htmlentities($name);
		$nameVerify=explode("-", $name);
		if (checkdate($nameVerify["2"], $nameVerify["1"], $nameVerify["0"])){
			if ($this->_published ==="oui"){
				return $this->_publishedDate = $name; 
			}else{
				return $this->_publishedDate =NULL; 
			}
		}
	}
}