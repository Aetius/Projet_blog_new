<?php

namespace App\Model; 
use App\Model\BDDConnection;
use PDO;
use App\App\controller\ArticleController;




class ArticleModel{
	private $bdd;
	private $description;
	private $dateCreation;
	private $content;
	private $id;
	private $result=[]; 
	private $published; 
	private $publishedDate; 
	private $title; 
	private $error=[]; 


	public function __construct(){		
		$this->bdd=(BDDConnection::Connection()); 
	}

	public function create(){
		$request=$this->bdd->prepare('INSERT INTO articles(title, description, content, publicated, date_creation, author_id) VALUES(:title, :description, :content, :publicated, :date_creation, :author_id )');
		$request->execute(array(
			'title'=>($this->title), 
			'description'=>($this->description),
			'content'=>($this->content),
			'publicated'=>($this->publishedDate),
			'date_creation'=>($this->setDate()),
			'author_id'=>("6")
			));
		$request->closeCursor();
	}

	public function all($numberArticles=10){
		$request=$this->bdd->prepare("SELECT * FROM articles ORDER BY id DESC LIMIT :number");
		$request->bindParam(':number', $numberArticles, PDO::PARAM_INT);
		$request->execute();
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			array_push($this->result, $result);
		}
		$request->closeCursor();
		return $this->result; 
	}

	public function readOne($id){
		$request=$this->bdd->prepare("SELECT * FROM articles WHERE id=:id");
		$request->execute(array(":id"=>$id));
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			array_push($this->result, $result);
		}
		$request->closeCursor();
		return $this->result; 
	}

	public function verifUse($name, $id){
		$request=$this->bdd->prepare("SELECT id FROM articles WHERE id=:$id");
		$request->execute(array($id=>$name));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result[$id];
	}

	private function deleteArticle($id){
		$request=$this->bdd->prepare("DELETE FROM articles WHERE id=:id");
		$result=$request->execute(array(":id"=>$id));
		$request->closeCursor(); 
		return $result;
	}
	

	/*functions calling the request, and return the result of this action .*/

	public function delete($id){
		return ($this->deleteArticle($id));
	}



	/*getters*/

	public function result(){
		return $this->result; 
	}

	public function error(){
		return $this->error;
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
			return $this->title = $name;
		}
	}

	public function setDescription($name){
		$name = $this->setVerification($name);
		return $this->description=$name; 
	}

	public function setContent($name){
		$name = $this->setVerification($name);
		return $this->content=$name; 

	}

	public function setPublished($name){
		$name = $this->setVerification($name);
		if(preg_match("#oui|non#", $name)){
			return $this->published; 
		}
	}

	public function setPublishedDate($name){
		$name=htmlentities($name);
		$nameVerify=explode("-", $name);
		if (checkdate($nameVerify["2"], $nameVerify["1"], $nameVerify["0"])){
			if ($this->published ==="oui"){
				return $this->publishedDate = $name; 
			}else{
				return $this->publishedDate =NULL; 
			}
		}
	}




}