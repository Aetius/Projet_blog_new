<?php

namespace App\controller; 

use App\controller\Controller;
use App\controller\ArticleController; 
use App\controller\CommentController; 
use App\Controller\UserController; 

class PostController extends Controller{
	private $controllerComment; 
	private $controllerArticle; 
	private $controllerUser; 

	public function __construct(){
		$this->controllerArticle = new ArticleController(); 
		$this->controllerComment = new CommentController(); 
		$this->controllerUser = new UserController(); 
	}

	/*public function showOneArticle($id){
		$articles = $this->articles($id);  
		$results['article']= $this->getUser($articles['oneArticle']); 
		
		$comments = $this->controllerComment->showComments($articles['oneArticle']['id']); 
		$results["comments"] = $this->getUsers($comments); 
		$this->show("oneArticlePage", $results, $articles['allArticles']);
		
		
	}*/
/*
	public function createComment(){  
		/*if (!isset ($_POST["articleId"])){
			header ("location:/articles");
		}; 
		if (isset($_POST["comment"])) {  
			$results = $this->controllerComment->create(); 
			$this->isCreate($results); 
		}else{
			$id=$_POST["articleId"];
			$articles = $this->articles($id); 
			$this->show("createComment", $articles); 
		}; 
		
	}

	private function isCreate($results){ 
		if (isset ($results['errors'])){
				$this->show('createComment', $results['errors']); 
		}else{
			$this->showOneArticle($results); 
		}
	}

	/*private function articles($id){
		$articles["oneArticle"] =  $this-> controllerArticle->oneArticlePage($id);
		$articles["allArticles"] = $this-> controllerArticle->allArticles(); 
		if ($articles['oneArticle']===false){
			header("location:/articles");
		}else{
			return $articles;
		}
	}*/

	/*private function getUser($oneArticle){
		$oneArticle['author'] = $this->controllerUser->defineUser($oneArticle["author_id"]);
		return $oneArticle;  
	
		  
	}

	private function getUsers($comments){ 
		foreach ($comments as $key => $value) {   
		 	$user = $this->controllerUser->defineUser($value["author"]);
			$comments[$key]["author_id"]= $user; 			
		} 
		return $comments;
	}*/


}