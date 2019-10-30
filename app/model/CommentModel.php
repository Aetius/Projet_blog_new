<?php

namespace App\Model; 

use PDO;
use App\model\AppModel;
use App\utilities\Validator;


class CommentModel extends AppModel{
	
	private $comment; 
	private $author; 
	private $published=null; 
	private $publishedDate=null;
	private $articleId; 	
	private $adminAnswer=null; 
	 

/**
* validations datas before execution in db
*/
	public function PrepareUpdate($inputs){ 
		$validationInput['isBool']['published']=$inputs['published']; 
		$this->validation($validationInput, "getValidatorUpdate");   
		
		if (!isset($this->errors)){
			return $this->isErrors($inputs); 
		};

		$fields=array(
			'publicated'=>$this->published, 
			'admin_answer'=>$this->adminAnswer
		);	
		return $this->recordValid('update', $fields);
	}


	public function prepareCreate($inputs){
		$date= $this->setDate(); 
		$this->validation($inputs);   
		if (!isset($this->errors)){
			return $this->isErrors($inputs); 
		};

		$fields = array(
			'article_id'=>($this->articleId),
			'author'=>($this->author), 
			'comment'=>($this->comment), 
			'date_comment'=>($date)
		);
		return $this->recordValid("create", $fields); 
	}



/*setters*/
	public function setAuthor($input){
		return $this->author = $input; 
	}

	public function setComment($input){
		return $this->comment = $input; 
	}

	public function setArticleId($input){
		return $this->articleId = $input; 
	}

	public function setDate(){
		$datetime = getdate(); 
		$date = $datetime['year']."-".$datetime['mon']."-".($datetime['mday']);
		return $date; 
	}

	public function setId($input){
		return $this->id=$input; 
	}

	public function setPublished($input){
		return $this->published=$input;  
	}

	public function setAdminAnswer($input){
		return $this->adminAnswer=$input; 
	}

	

/*getters*/
	public function articleId(){
		return $this->articleId; 
	}

	public function id(){
		return $this->id; 
	}

	public function published(){
		return $this->published; 
	}


/**
*generic validator 
*/
	protected function getValidator($inputs){
		return(new Validator($inputs))
			->length('comment', 5)
			->length('author', 3)
			->required(['author', 'comment', 'articleId']);
	}
	

}
