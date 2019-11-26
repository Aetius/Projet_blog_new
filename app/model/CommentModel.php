<?php

namespace App\Model; 


use App\Utilities\Validator;


class CommentModel extends AppModel{
	
	private $comment; 
	private $author; 
	private $published=null;
	private $articleId; 	
	private $adminAnswer=null; 
	 

	/**
	 *Prepare datas before update of the article
	 *@param array $inputs
	 *@return bool 
	 */
	public function PrepareUpdate($inputs){ 
		if ((!isset ($inputs['published']))|| (!isset ($this->id))){
			$this->errors[]="Erreur lors de l'enregistrement";
			return false; 
		}
		$validationInput['isBool']['published']=$inputs['published'];
		$this->validation($validationInput, "getValidatorUpdate");
		
		if (!isset($this->errors)){
			return false;  
		};

		$fields=array(
			'publicated'=>$this->published, 
			'admin_answer'=>$this->adminAnswer
		);	
		return $this->recordValid('update', $fields);
	}

	/**
	 *Prepare datas before creation of the comment
	 *@param array $inputs
	 *@return bool 
	 */
	public function prepareCreate($inputs){
		$date= $this->setDate(); 
		$this->validation($inputs);   
		if (!empty($this->errors)){ 
			return false; 
		};

		$fields = array(
			'article_id'=>($this->articleId),
			'author'=>($this->author), 
			'comment'=>($this->comment), 
			'date_comment'=>($date)
		);
		return $this->recordValid("create", $fields); 
	}



	/**
	 *Setters
	 */
    /**
     * @param $input
     * @return string
     */
	public function setAuthor($input){
		return $this->author = $input; 
	}

    /**
     * @param $input
     * @return string
     */
    public function setComment($input){
		return $this->comment = $input; 
	}

    /**
     * @param $input
     * @return string
     */
	public function setArticleId($input){
		return $this->articleId = $input; 
	}

    /**
     * @param $input
     * @return string
     */
	public function setDate(){
		$datetime = getdate(); 
		$date = $datetime['year']."-".$datetime['mon']."-".($datetime['mday']);
		return $date; 
	}

    /**
     * @param $input
     * @return int
     */
	public function setId($input){
		return $this->id=intval($input); 
	}

    /**
     * @param $input
     * @return bool
     */
	public function setPublished($input){
		return $this->published=$input;  
	}

    /**
     * @param $input
     * @return bool
     */
	public function setAdminAnswer($input){
		return $this->adminAnswer=$input; 
	}

	

	/**
	 *Getters
	 */
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
	 *Validator verification
     * @var array $inputs
	 *@return object
	 */
	protected function getValidator($inputs){
		return(new Validator($inputs))
			->length('comment', 5)
			->length('author', 3)
			->required(['author', 'comment', 'articleId']);
	}
	

}
