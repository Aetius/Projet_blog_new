<?php

namespace App\Model; 

//use PDO;
/*use App\utilities\Purifier;*/
use App\utilities\Validator;
use App\model\AppModel; 
use App\utilities\AppFactory;




class ArticleModel extends AppModel{
	//private $bdd;
	private $description;
	private $dateCreation;
	private $content;
	private $id;
	private $published=null; 
	private $publishedDate=null; 
	private $title; 
	private $authorId; 


/*functions calling*/
	public function allArticles(){
		$articles = $this->all(); 
		foreach ($articles as $key => $value) {
			if (array_key_exists("content",$value)){
				($articles[$key]['content'] = htmlspecialchars_decode($value['content'])); 
			}
		}
		return $articles; 
	}


	public function prepareUpdatePublished($inputs){ 
		$validationInput['isBool']['published']=$inputs['published']; 
		$this->validation($validationInput, 'getValidatorUpdate'); 

		if (!empty($this->errors)){
			return $this->isErrors($inputs); 
		}

		$fields=array(
			'publicated'=>$this->published,
			'date_publication'=>$this->publishedDate
		);
		return $this->creationSuccess('update', $fields);
	}


	public function prepareUpdate/*updateArticle*/($inputs){ 
		$this->validation($inputs);
		$article=$this->one('title', $this->title);  
		if ( ((($article['title'])===($this->title))&&($article['id']!==$this->id))){
			array_push($this->errors, "Le titre de cet article existe déjà!");
		};

		if (!empty($this->errors)){
			return $this->isErrors($inputs); 
		}

		$fields=array(
			'title'=>$this->title, 
			'content'=>$this->content, 
			'date_update'=>$this->setDate(), 
			'description'=>$this->description, 
			'publicated'=>$this->published,
			'date_publication'=>$this->publishedDate
		);
		return $this->creationSuccess('update', $fields);
	}


	public function prepareCreate($inputs){ 
/*
		if (isset($_SESSION['user']['id'])){
			$result = $this->hydrate($_SESSION['user']);
			$this->setAuthorId($result['id']);
			
		}else{
			$factory = AppFactory::getInstance(); 
			$userModel = $factory->getModel('user');
			$result = $this->hydrate($_SESSION['access']);
			$id = $userModel->one('login', $result['login']); 
			$this->setAuthorId($id['id']); 
		}*/

			
		$this->validation($inputs);
		$article=$this->one('title',$this->title);  
		if ((($article['title'])===($this->title))){ 
			$this->errors[]="Le titre de cet article existe déjà!";
		};

		if (!is_null($this->errors)){
			return $this->isErrors($inputs); 
		};

		$fields = array(
			'title'=>($this->title), 
			'description'=>($this->description),
			'content'=>($this->content),
			'publicated'=>($this->published),
			'date_publication'=>($this->publishedDate), 
			'date_creation'=>($this->setDate()),
			'author_id'=>($this->authorId)
		);
		 
		return $this->creationSuccess('create', $fields);
	}





	/*getters*/

	public function errors(){
		return $this->errors;
	}

	public function title(){
		return $this->title; 
	}

	public function description(){
		return $this->description; 
	}

	public function content(){
		return $this->content; 
	}

	public function id(){
		return $this->id; 
	}

	////////////////setters///////////////
	/*public function setVerification($Purifier){
		return \App\utilities\Purifier::htmlPurifier($Purifier);
	}*/

	public function setDate(){
		$datetime = getdate(); 
		$date = $datetime['year']."-".$datetime['mon']."-".($datetime['mday']); 
		return $date;
	}


	public function setTitle($input){  
		//$name = $this->setVerification($name);
			return $this->title = $input;
	}

	public function setDescription($name){
		//$name = $this->setVerification($name);
		return $this->description=$name; 
	}

	public function setContent($name){
		//$name = $this->setVerification($name);
		return $this->content=$name; 

	}

	public function setPublished($name){
		
		//$name = $this->setVerification($name);
		
			return $this->published=$name; 
	
	}

	public function setPublishedDate($name){
		//$name=htmlentities($name);
		$nameVerify=explode("-", $name);
		if (checkdate($nameVerify["1"], $nameVerify["2"], $nameVerify["0"])){
			if ($this->published === "1"){ 
				return $this->publishedDate = $name; 
			}else{
				return $this->publishedDate =NULL; 
			}
		}
	}

	public function setId($name){
		if ($this->one( 'id', $name)){
			return $this->id = $name; 
		}
		//$name = $this->setVerification($name);
	}

	public function setAuthorId($name){ 
			$this->authorId = $name; 
	}


	

	protected function getValidator($inputs){
		
		return(new Validator($inputs))
			->length('content', 1)
			->length('description', 1)
			->length('title', 1)
			->name('title');
			
	}

}