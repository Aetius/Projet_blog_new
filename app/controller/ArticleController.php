<?php
namespace App\controller; 

use App\controller\abstractController;
use App\Model\ArticleModel;
use App\controller\UserController;




class ArticleController /*extends abstractController*/ {
	private $modelArticles;
	private $controllerUser;
	private $success=[];


	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		$this->controllerUser = new UserController(); 
		$this->session(); 
	}

///////////show functions to call the pages article. /////////////
	protected function show($id, $result=[]){
		$twig = new \View\twigView();
		$twig->show("/article/$id", $result);
	}


	public function showDashboard(){
		$result=$this->modelArticles->all();
		$result['success']= $this->success;

		$this->show('dashboardPage', $result);
	}

	public function showArticles(){ 
		$result = $this->modelArticles->all(); 
		$this->show("articlePage", $result);
	}
	
	public function showOneArticle($id, $page="oneArticlePage"){
		$result = $this->modelArticles->readOne($id);
		if ($result!=false){
			$this->show("$page", $result);
		}else{
			header("location:/articles");
		}

	}

	public function showCreate(){
		$this->show("createArticle");
	}

	public function showUpdate($idArticle){
		if ($this->controllerUser->admin()===true){ 
			$this->showOneArticle($idArticle, "updateArticle");
		}
	}



/*creation update and delete pages*/
	public function create(){
		$this->modelArticles->hydratePost(); 
		$inscription = 0;
		if (empty($this->modelArticles->error())){
			$this->modelArticles->create();
			$inscription = ["success"=> 1];
		}else{
			$inscription = ["success"=> 2];
		}
		$this->show('createArticle', $inscription);	
	}

	public function dashboard(){

		$postValue = $this->modelArticles->hydratePost();
		if (empty($this->modelArticles->error())){
			if (array_key_exists("Delete", $postValue)){
				$this->delete($postValue);
			}elseif (array_key_exists("Update", $postValue)) {
				header("location:/articles/update/".$postValue['Update']);
			}
		}else{
			//$this->show('dashboardPage', $this->error);
		}
	}


	public function update($idArticle){
		$this->modelArticles->setId($idArticle);
		$this->modelArticles->hydratePost(); 
		if (empty($this->modelArticles->error())){
			if ($this->modelArticles->update()===true){
				$this->success= 1;
			}
		}else{
			$this->success= 2;
		}
		$this->show('dashboardPage', $this->success);
		header("location:/connexion/dashboard", false);
		var_dump($this->success); 
		die();

	}

	private function delete($postValue){
		//vérifier les habilitations du user.
		if (is_int($postValue['Delete'])){
			if ($this->modelArticles->delete($postValue['Delete'])===true){
				$this->result="l'article a bien été supprimé";
				$this->showDashboard();
			}
		}
	}




	




	// this function verifies the inputs by sending them in a set function in the articleModel. 
	/*private function hydratePost(){
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
	}*/


	/*start $_session if it is not already launch*/

	private function session(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
	}
}

