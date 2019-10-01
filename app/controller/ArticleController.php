<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\ArticleModel;
use App\Model\UserModel; 
use App\controller\TwigController; 
use App\Model\CommentModel; 



class ArticleController extends Controller{
	private $modelArticles;
	private $modelUser;
	private $modelComment; 


	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		$this->modelUser = new UserModel(); 
		$this->modelComment = new CommentModel(); 
	
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


	public function showAll(){ 
		$results = $this->allArticles(); 
		$this->show("articlePage", $results);
	}
	



public function showOne($id){
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			header("location:/articles");
		}else{
			$articles= $this->allArticles(); 
			$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
			$this->show("createComment", $results, $articles);
		};
		
	}


	public function showDashboard(){
		$results['articles']=$this->modelArticles->allArticles();
		$results['comments']=$this->allComments(); 
		//$pagination = $this->definePagination($result, $id);
		//$showResult = $this->defineArticlesShow($result, $pagination); var_dump($showResult); die(); 
		$this->show('dashboard', $results);
	}



	/*private function articles($id){
		$articles["oneArticle"] =  $this-> controllerArticle->oneArticlePage($id);
		$articles["allArticles"] = $this-> controllerArticle->allArticles(); 
		
			return $articles;
		}
	}*/




	private function allArticles(){
		return $this->modelArticles->all(); 
	}


	private function oneArticle($id){ 
		$article['id']=$id; 
		$this->modelArticles->hydrate($article); 
		$article = $this->modelArticles->one('id', $this->modelArticles->id());
		if ($article){
		$article['author'] = $this->defineUser($article["author_id"]);
			return $article; 
		}
	}

	private function allComments(){
		return $this->modelComment->allComments();
	}

	private function commentsByArticle($idArticle){
		return $this->defineUserComment($this->modelComment->commentsByArticle($idArticle)); 		
	}


	private function defineUserComment($comments){ 
		//$comments = $this->modelComment->allComments($published, $idArticle, $request); 
		foreach ($comments as $key => $value) {   
		$user = $this->defineUser($value["author"]);
			$comments[$key]["author_id"]= $user; 			
		} var_dump($comments); die(); 
		return $comments;
	}

	
	private function defineUser($userId){ 
		$userParams = $this->modelUser->one('id', $userId); 
		$user=$userParams['login']; 
		return $user; 
	}













		/*show functions with restricted access*/
	public function showCreate(){
		
		$this->show("createArticle");
	}

	public function showUpdate($idArticle){
		  
		$results = $this->oneArticlePage($idArticle); 
		
		$this->show("createArticle",$results);
	}

	public function showCreateComment($idArticle){
		 
		$results = $this->oneArticlePage($idArticle);
		$this->show('createComment', $results);
	}

	

	/*private function restrictedAccess(){
		if (!$this->controllerUser->admin()){ 
			return header("location:/connexion");
		}
	}*/

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
/*	public function dashboard(){ 
		$this->restrictedAccess(); 
		$postValue = $this->modelArticles->hydrate(); 
		
		if (array_key_exists("Delete", $postValue)){
			$this->delete($postValue);
		}elseif (array_key_exists("Update", $postValue)) {
			header("location:/connexion/update/".$postValue['Update']);
		}else{
			header("location:/connexion/dashboard"); 
		}
	}*/


	public function create(){
		
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

	public function delete(){
		$delete = $this->deleteArticle(); 
	
		if ($delete===true){
			$_SESSION['success'][1]="L'article a bien été supprimé.";
		}else{
			$_SESSION['success'][2]="Echec de la suppression!!";
		}
		header("location:/connexion/dashboard");
	}

	public function deleteArticle(){
		$postValue = $this->modelArticles->hydrate(); 
		if (!is_null(intval($postValue['Delete']))){
			return $this->modelArticles->delete($postValue['Delete']);
		}; 
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

