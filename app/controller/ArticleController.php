<?php
namespace App\controller; 

use App\Model\ArticleModel;
use App\controller\Verification;
use App\controller\SessionController; 



class ArticleController{
	private $_modelArticles; 
	private $_error=[]; 


	public function __construct(){
		$this->_modelArticles = new ArticleModel(); 
	}

///////////show functions to call the pages article. /////////////
	public function show($id, $result=[]){
		$twig = new \View\twigView();
		$twig->show("/article/$id", $result);
	}

	public function showArticles(){
		$this->_modelArticles->read(); 
		$result = $this->_modelArticles->result();
		$this->show("articlePage", $result);
	}
	
	public function showOneArticle($id){
		$this->_modelArticles->readOne($id);
		$result = $this->_modelArticles->result();
		$this->show("oneArticlePage", $result);
	}

	public function showCreate(){
		$this->show("createArticle");
	}

///////this function verifies the inputs by sending them in a set function in the articleModel. //////////////
	public function verificationPost(){
		foreach ($_POST as $key => $value) {
			$name = "set".$key;
			$this->_modelArticles->$name($value); 
			
			if ((!($this->_modelArticles->error)=="")&& (!(in_array($this->_modelArticles->error, $this->_error)))){
				 (array_push($this->_error, $this->_modelArticles->error)); 
			};
		}
	}

	public function create(){
		$this->verificationPost(); 
		$inscription = 0;
		if (empty($this->_error)){
			$this->_modelArticles->create();
			$inscription = ["success"=> 1];
		}else{
			$inscription = ["success"=> 2];
		}
		$this->show('createArticle', $inscription);	
	}
}

