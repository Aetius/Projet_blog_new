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


	



}