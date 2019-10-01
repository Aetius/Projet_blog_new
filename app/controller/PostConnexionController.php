<?php

namespace App\controller; 


use App\controller\Controller;
use App\controller\ArticleController; 
use App\controller\CommentController; 
use App\Controller\UserController; 


class PostConnexionController extends Controller{

	private $controllerComment; 
	private $controllerArticle; 
	private $controllerUser; 


	public function __construct(){
		$this->controllerArticle = new ArticleController(); 
		$this->controllerComment = new CommentController(); 
		$this->controllerUser = new UserController(); 
		sessionController::getSession();
	}
	
	public function dashboard($idArticle){ 
		$results['article'] = $this-> controllerArticle->oneArticlePage($idArticle);
		$results['comments'] = $this->controllerComment->dashboard($idArticle); 

		if ($results['article'] === false){
			header("location:/connexion/dashboard");
		}else{
			$this->show('dashboardComments', $results);
		}
	}

	public function routes(){
		if (array_key_exists("Delete", $_POST)){
			$this->deleteArticle();
		}elseif (array_key_exists("Update", $postValue)) {
			header("location:/connexion/update/".$postValue['Update']);
		}else{
			header("location:/connexion/dashboard"); 
		}
	}
	

	public function deleteArticle(){  
		$article = $this->controllerArticle->deleteArticle();
		$comments = $this->controllerComment->deleteComments(); 
		if ($article === true && $comments === true){
			$_SESSION['success'][1]="L'article et les commentaires associés ont bien été supprimés.";
		}else{
			$_SESSION['success'][2]="Echec lors de la suppression!!";
		}
		header("location:/connexion/dashboard"); 
	}
		



	



}