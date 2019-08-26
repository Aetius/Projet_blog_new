<?php
namespace App\controller; 

use App\controller\abstractController;
use App\Model\ArticleModel;
use App\utilities\Verification;




class ArticleController /*extends abstractController*/ {
	private $modelArticles; 
	private $error=[]; 


	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		$this->session(); 
	}

///////////show functions to call the pages article. /////////////
	protected function show($id, $result=[]){
		$twig = new \View\twigView();
		$twig->show("/article/$id", $result);
	}


	public function showDashboard(){
		$result=$this->modelArticles->all();
		
		$this->show('dashboardPage', $result);
	}

	public function showArticles(){//appeler get. 
		$this->modelArticles->all(); 
		$result = $this->modelArticles->result();//à l'intérieur du modèle. 
		$this->show("articlePage", $result);
	}
	
	public function showOneArticle($id){
		$this->modelArticles->readOne($id);
		$result = $this->modelArticles->result();//à l'intérieur du modèle. 
		$this->show("oneArticlePage", $result);
	}

	public function showCreate(){
		$this->show("createArticle");
	}



/*creation update and delete pages*/
	public function create(){
		$this->verificationPost(); 
		$inscription = 0;
		if (empty($this->error)){
			$this->modelArticles->create();
			$inscription = ["success"=> 1];
		}else{
			$inscription = ["success"=> 2];
		}
		$this->show('createArticle', $inscription);	
	}

	public function dashboard(){
		$postValue = $this->verificationPost();
		if (empty($this->error)){
			if (array_key_exists("Delete", $postValue)){
				$this->delete($postValue);
			}
		}else{
			//$this->show('dashboardPage', $this->error);
		}
	}

	private function delete($postValue){
		//vérifier les habilitations du user.
		if (is_int($postValue['Delete'])){
			if ($this->modelArticles->delete($postValue['Delete'])===true){
				$result=[];

				$this->showDashboard();
			}
		}

	}




	// this function verifies the inputs by sending them in a set function in the articleModel. 
	private function verificationPost(){
		$allKey=[];
		foreach ($_POST as $key => $value) {
			$key = Verification::input($key);
			$value = Verification::input($value); 
			$name = "set".$key;
			$allKey= [$key=>$value];
			if(method_exists($this->modelArticles, $name)){
				$this->modelArticles->$name($value); 
			
			};
			if ((!($this->modelArticles->error())=="")){
				$this->error= $this->modelArticles->error(); 
			}
			return $allKey;
		}
	}


	/*start $_session if it is not already launch*/

	private function session(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
	}
}

