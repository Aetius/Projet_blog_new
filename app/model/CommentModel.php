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
	private $id; 
	private $adminAnswer=null; 
	 

/*execute in bdd*/ 
	
	public function allComments(){
		return $this->prepareRequest("SELECT * FROM comments ORDER BY id DESC");
	}
// à virer
	/*public function commentsByPublished($publicated){
		return $this->prepareRequest("SELECT * FROM comments WHERE publicated=:publicated ORDER BY id DESC", [":publicated"=>$publicated]);
	}*/

	public function search($fieldName, $field){  
		return $this->prepareRequest("SELECT * FROM comments WHERE $fieldName=:fieldName ORDER BY id DESC", [":fieldName"=>$field]);
	}
	
	public function updateComment(){
		$fields=array(
			'publicated'=>$this->published, 
			'admin_answer'=>$this->adminAnswer
		);

		return $this->creationSuccess('update', $fields);
	}
/*functions calling*/
	
/*
	public function read($id){
		
		$request=$this->bdd->prepare("SELECT * FROM comments WHERE article_id=$id ORDER BY id DESC");
		$request->execute(array(
			":idArticle"=>$id));
		$allResult=[];
		while ($result=$request->fetch(PDO::FETCH_ASSOC)){
			foreach ($result as $key => $value) {
				$result[$key]=(htmlspecialchars_decode($value));
				$result['login']=$this->readAuthor($result['author_id']);
			}
			array_push($allResult, $result);
		}	

		$request->closeCursor();
		return $allResult; 
	}*/

	/*private function readAuthor($loginId){
		$request=$this->bdd->prepare("SELECT login FROM users WHERE id=$loginId");
		$request->execute(array(
			"id"=>$loginId));
		$result=$request->fetch(PDO::FETCH_ASSOC);
		$request->closeCursor();   
	
		return $result['login'];
	}*/
	

	/*public function verif($id, $name){

	}*/

/*verification if all inputs are here, and start the request. */

	public function createComment($inputs){
		$date= $this->setDate(); 
		$this->validation($inputs); 
		
		$fields = array(
			'article_id'=>($this->articleId),
			'author'=>($this->author), 
			'comment'=>($this->comment), 
			'date_comment'=>($date)
		);
		return $this->creationSuccess("create", $fields); 

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


	
	protected function getValidator($inputs){
		return(new Validator($inputs))
			->length('comment', 5)
			
			->length('author', 3)
			->required(['author', 'comment', 'articleId']);
	}
	

}
