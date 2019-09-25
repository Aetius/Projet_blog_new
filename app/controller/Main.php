<?php

namespace App\controller; 


class Main extends Controller{
	private $controllerUser; 
	private $controllerArticle; 
	private $controllerComment;

	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		$this->controllerUser = new UserController(); 
		
		sessionController::getSession(); 
	}







}