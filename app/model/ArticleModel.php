<?php

namespace App\Model; 
use App\Model\BDDConnection;
use PDO;
use App\App\controller\ArticleController;
use App\utilities\Purifier;




class ArticleModel{
	private $bdd;
	private $description;
	private $dateCreation;
	private $content;
	private $id;
	private $published=null; 
	private $publishedDate=null; 
	private $title; 
	private $error=[]; 


	public function __construct(){		
		$this->bdd=(BDDConnection::connection()); 
	}

	public function create(){

		$request=$this->bdd->prepare('INSERT INTO articles(title, description, content, publicated, date_creation, author_id, date_publication) VALUES(:title, :description, :content, :publicated, :date_creation, :author_id, :date_publication )');
		$request->execute(array(
			':title'=>($this->title), 
			':description'=>($this->description),
			':content'=>($this->content),
			':publicated'=>($this->published),
			':date_publication'=>($this->publishedDate), 
			':date_creation'=>($this->setDate()),
			':author_id'=>("6")
			));
		$request->closeCursor();
	}

	public function all($numberArticles=10){
		$request=$this->bdd->prepare("SELECT * FROM articles ORDER BY id DESC");
		$request->execute();
		$allResult=[];
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			foreach ($result as $key => $value) {
				$result[$key]=(htmlspecialchars_decode($value));
			}
			array_push($allResult, $result);
		}
		$request->closeCursor();
		return $allResult; 
	}

	public function readOne($id){
		$request=$this->bdd->prepare("SELECT * FROM articles WHERE id=:id");
		$request->execute(array(":id"=>$id));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		foreach ($result as $key => $value) {
				$result[$key]=(htmlspecialchars_decode($value));
			}
		$request->closeCursor();
		return $result; 
	}

	public function verifUse($name, $id){
		$request=$this->bdd->prepare("SELECT id FROM articles WHERE id=:$id");
		$request->execute(array($id=>$name));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor(); 
		return $result[$id];
	}

	public function delete($id){
		$request=$this->bdd->prepare("DELETE FROM articles WHERE id=:id");
		$result=$request->execute(array(":id"=>$id));
		$request->closeCursor(); 
		return $result;
	}
	

	public function update(){
		$request=$this->bdd->prepare ('UPDATE articles SET title=:title, content=:content, date_update=:dateUpdate, description=:description, publicated=:publicated, date_publication=:date_publication WHERE id=:id');
	/*var_dump($this->title);
		die();*/
		$result=$request->execute(array(
			':title'=>$this->title, 
			':content'=>$this->content, 
			':dateUpdate'=>$this->setDate(), 
			':description'=>$this->description, 
			':publicated'=>$this->published,
			':date_publication'=>$this->publishedDate,
			':id'=>$this->id
		));
		$request->closeCursor(); 
		return $result;
	}




	/*getters*/

	public function error(){
		return $this->error;
	}


	////////////////setters///////////////
	public function setVerification($Purifier){
		return \App\utilities\Purifier::text($Purifier);
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

	public function setId($name){
		$name = $this->setVerification($name);
			return $this->id = $name; 
		}


	public function hydratePost(){
		$allKey=[];
		foreach ($_POST as $key => $value) {
			$key = Purifier::input($key);
			//$value = Purifier::input($value); 
			$value=Purifier::htmlPurifier($value);
			$name = "set".$key;
			$allKey= [$key=>$value];
			if(method_exists($this, $name)){
				$this->$name($value); 
			};
		}
			return $allKey;
	}



}