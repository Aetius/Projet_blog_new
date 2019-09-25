<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\ArticleModel;
use App\controller\UserController; 

use App\controller\TwigController; 



class ArticleController extends Controller{
	private $modelArticles;
	private $controllerUser;
	


	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		$this->controllerUser = new UserController(); 
		
		sessionController::getSession(); 
	}

///////////show functions to call the pages article. /////////////
/*	protected function show($id, $result=[], $options=[]){
		if (isset($_SESSION['success'])){
			$result['success']= $_SESSION['success'];
			unset($_SESSION['success']);
		}
		
		/*$twig = new Twig();
		$twig->show("/$id", $result, $options);
	}*/


	public function showArticles(){ 
		$result = $this->modelArticles->all(); 

		$this->show("articlePage", $result);
	}
	
	public function oneArticlePage($id){ 
		$article['id']=$id; 
		$this->modelArticles->hydrate($article); 
		return $this->modelArticles->one('id', $this->modelArticles->id());
	}

	public function allArticles(){
		return $this->modelArticles->all(); 
	}


		/*show functions with restricted access*/
	public function showCreate(){
		$this->restrictedAccess(); 
		$this->show("createArticle");
	}

	public function showUpdate($idArticle){
		$this->restrictedAccess();  
		$results = $this->oneArticlePage($idArticle); 
		
		$this->show("createArticle",$results['oneArticle']);
	}

	public function showCreateComment($idArticle){
		$this->restrictedAccess(); 
		$results = $this->oneArticlePage($idArticle);
		$this->show('createComment', $results);
	}

	public function showDashboard($id=0){
		$this->restrictedAccess(); 
		$result=$this->modelArticles->allArticles();
		$pagination = $this->definePagination($result, $id);
		//$showResult = $this->defineArticlesShow($result, $pagination); var_dump($showResult); die(); 
		$this->show('dashboardArticlePage', $result, $pagination);
	}

	private function restrictedAccess(){
		if (!$this->controllerUser->admin()){ 
			return header("location:/connexion");
		}
	}

	/*private function defineArticlesShow($result, $pagination){
		$displayResult=[];  
		foreach ($pagination as $key => $value) {
			if (array_key_exists("page", $value)){
				$min=$pagination[$key]['min'];
				$max=$pagination[$key]['max'];
			}
		}
		foreach ($result as $key => $value) {
			if ($key>=$min && $key<=$max){
				$result[$key]['comment']=$this->controllerComment->read($result[$key]['id']);
				array_push($displayResult, $result[$key]);
			}
		}	
			
		return($displayResult);		
	}*/

	private function definePagination($result, $id){
		$page=[]; 
		$nbArticles=6;
		$min=0; 
		$max=5; 
		$resultLength=(count($result));
		$resultByPage = ceil(($resultLength)/$nbArticles);
		
		for ($i=1; $i<=$resultByPage; $i++){
			$page[$i]=array(
					"min" => $min, 
					"max" => $max	
				);
			$min +=$nbArticles; 
			$max +=$nbArticles;
		}

			if (array_key_exists($id, $page)){
				$page[$id]["page"]='active';
			}else{
				$page["1"]["page"]='active';
			}	
		return ($page); 
	}




/*creation update and delete pages*/
	public function dashboard(){ 
		$this->restrictedAccess(); 
		$postValue = $this->modelArticles->hydrate(); 
		
		if (array_key_exists("Delete", $postValue)){
			$this->delete($postValue);
		}elseif (array_key_exists("Update", $postValue)) {
			header("location:/connexion/update/".$postValue['Update']);
		}else{
			header("location:/connexion/dashboard"); 
		}
	}


	public function create(){
		$this->restrictedAccess(); 
		$inputs = $this->modelArticles->hydrate(); 
		//$this->modelArticles->validation($inputs); 
		$result = $this->modelArticles->createArticle($inputs);
		if ($result===true){
			$_SESSION["success"][1]= "L'article est crée";
			header("location:/connexion/dashboard");
		};
		
		$_SESSION["success"][2]="Impossible de créer l'article";
		$this->show('createArticle', $result);	
	}


	public function update($idArticle){
		$this->restrictedAccess();  
		$this->modelArticles->setId($idArticle);
		$inputs = $this->modelArticles->hydrate(); 
		//$this->modelArticles->validation($inputs); 
		$result = $this->modelArticles->updateArticle(); 

		if ($result===true){
			$_SESSION["success"][1]="Mise à jour de l'article effectuée";
			header("location:/connexion/dashboard");
		}else{
			$_SESSION["success"][2]="La mise à jour de l'article a échoué";
			$result['errors']= $this->modelArticles->error();
			$this->show('createArticle',$result);
		}
	}

	private function delete($postValue){
		$this->restrictedAccess(); 
		if (!is_null(intval($postValue['Delete']))){
			if ($this->modelArticles->delete($postValue['Delete'])===true){
				$_SESSION['success'][1]="L'article a bien été supprimé.";
			}
		}else{
			$_SESSION['success'][2]="Echec de la suppression!!";
		}
		header("location:/connexion/dashboard");
	}




	




	// this function verifies the inputs by sending them in a set function in the articleModel. 
	/*private function hydrate(){
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
/*
	private function session(){
		if (session_status()===PHP_SESSION_NONE){
			session_start(); 
		}
	}*/
}

