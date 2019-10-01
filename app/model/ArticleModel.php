<?php

namespace App\Model; 

use PDO;
/*use App\utilities\Purifier;*/
use App\utilities\Validator;
use App\model\AppModel; 




class ArticleModel extends AppModel{
	//private $bdd;
	private $description;
	private $dateCreation;
	private $content;
	private $id;
	private $published=null; 
	private $publishedDate=null; 
	private $title; 


/*functions calling*/
	public function allArticles(){
		$articles = $this->all(); 
		foreach ($articles as $key => $value) {
			if (array_key_exists("content",$value)){
				($articles[$key]['content'] = htmlspecialchars_decode($value['content'])); 
			}
		} return $articles; 
	}


	public function updateArticle(){
		$dataBdd=$this->verifUse($this->title, 'title'); 
		if ((strtoupper($dataBdd['title'])===strtoupper($this->title))&&($dataBdd['id']!==$this->id)){
			$this->errors="Le titre de cet article existe déjà!";
		};

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


	public function createArticle($inputs){
		 $this->validation($inputs);
		$dataBdd=$this->verifUse($this->title, 'title');
		if ((strtoupper($dataBdd['title'])===strtoupper($this->title))){
			$this->errors[]="Le titre de cet article existe déjà!";
		} 
		$fields = array(
			'title'=>($this->title), 
			'description'=>($this->description),
			'content'=>($this->content),
			'publicated'=>($this->published),
			'date_publication'=>($this->publishedDate), 
			'date_creation'=>($this->setDate()),
			'author_id'=>("6")
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
		if(preg_match("#oui|non#", $name)){
			return $this->published; 
		}
	}

	public function setPublishedDate($name){
		//$name=htmlentities($name);
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
		if ($this->verifUse( $name,'id')){
			return $this->id = $name; 
		}
		//$name = $this->setVerification($name);
	}


	

	protected function getValidator($inputs){
		return(new Validator($inputs))
			->length('Content', 1)
			->length('Description', 1)
			->length('Title', 1)
			->name('Title'); 
	}

}