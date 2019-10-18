<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\ArticleModel;


class ArticleController extends Controller{
	private $modelArticles;
	

	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		parent::__construct(); 		
	}


	public function showAll($idPage){  	
		$results['articles'] = $this->allArticles(); 
		$results = $this->articleDisplay($results, $idPage); 

		$this->show("/public/dashboard", $results);
	}


	public function showOne($id){ 
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			header("location:/articles");
		}else{
			$articles= $this->allArticles(); 
			$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
			$this->show("/createComment", $results, $articles);
		};
	}


	public function showDashboard(){
		$results['articles']=$this->allArticles();
		$results['comments']=$this->allComments(); 
		$this->show('/admin/dashboard', $results);
	}


	public function showAllAdmin($idPage){  
		$results['articles']=$this->allArticles();
		$results = $this->articleDisplay($results, $idPage); 
		$results['comments']=$this->allComments(); 
		$this->show('/admin/articles', $results);
	}


	public function showOneAdmin($id, $inputsError=[]){ 
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			header("location:/admin/articles");
		}else{
			$articles= $this->allArticles();  
			$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
			$results = $results + $inputsError; 
			$this->show("/admin/article", $results, $articles);
		};
	}

		
	public function showCreate(){
		$this->show("/admin/create");
	}


	public function create(){  
		$input['authorId']= $_SESSION['user']['id']; 
		$inputs = $this->modelArticles->hydrate($input); 
		$inputs = $inputs + $this->modelArticles->hydrate($_POST);
		$result = $this->modelArticles->prepareCreate($inputs);
 
		if ($result===null){
			$_SESSION["success"][1]= "L'article est crée";
			header("location:/admin/dashboard");
		}else{
			$_SESSION["success"][2]="Impossible de créer l'article";
			$this->show('/admin/create', $result);	
		}
	}


	public function updatePublication(){  
		$this->update($_POST, 'prepareUpdatePublished'); 
	}


	public function updateArticle($idArticle){ 
		$inputs = $_POST;
		$inputs["id"]=$idArticle; 
		$this->update($inputs, "prepareUpdate"); 
	}


	public function delete(){ 
		$inputs = $this->modelArticles->hydrate($_POST); 
		if (!is_null(intval($inputs['delete']))){
			$modelComment = $this->factory->getModel('comment'); 
			$delete["article"]=$this->modelArticles->delete($inputs['delete']);
			$delete["comments"]=$modelComment->delete($inputs['delete'], 'article_id'); 
		}; 
 
		if (($delete["article"]===true) && ($delete["comments"]===true)) {
			$_SESSION['success'][1]="L'article a bien été supprimé.";
		}else{
			$_SESSION['success'][2]="Echec de la suppression!!";
		}
		header("location:/admin/articles");
	}




	private function update($inputsVerif, $method){ 
		$inputs= $this->modelArticles->hydrate($inputsVerif); 
		$result = $this->modelArticles->$method($inputs); 

		if (is_null($result)){ 
			$_SESSION["success"][1]="Mise à jour de l'article effectuée";
			return header("location:/admin/articles");
		}else{
			$_SESSION["success"][2]="La mise à jour de l'article a échoué"; 
			$result['errors']= $this->modelArticles->errors(); 
			return $this->showOneAdmin($result['inputsError']['id'], $result); 
		}
	}


	private function allArticles(){
		$allArticles=$this->defineAllUsers($this->modelArticles->all()); 
		return $allArticles; 
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
		$modelComment = $this->factory->getModel('Comment'); 
		return $modelComment->all();
	}

	private function commentsByArticle($idArticle){
		$modelComment = $this->factory->getModel('Comment');  
		return $modelComment->search( "article_id", $idArticle); 		
	}


	private function defineAllUsers($articles){ 
		foreach ($articles as $key => $value) {    
		$user = $this->defineUser($value["author_id"]);
			$articles[$key]["author_name"]= $user; 			
		}  
		return $articles;
	}

	
	private function defineUser($userId){ 
		$modelUser = $this->factory->getModel('User'); 
		$userParams = $modelUser->one('id', $userId); 
		$user=$userParams['login']; 
		return $user; 
	}


	private function articleDisplay($results, $idPage){
		$articlesByPage = 10; 
		$lenArticles = count($results['articles']); 

		$input['num']=$idPage; 
		$results['page'] = $this->modelArticles->hydrate($input);
		
		if ((!isset($results['page']['num'])) || (empty($results['page']['num'])) || ($results['page']['num'] < 1 )){
			$results['page']['num'] = 1; 
		}; 
		if ((($lenArticles/10)+1)< $results['page']['num']){
			$page = ceil($lenArticles/10); 
			header("location:/admin/articles/page/$page");
		} 

		$results['page']['min'] = ($results['page']['num'] -1) * $articlesByPage;
		$results['page']['max'] = (($results['page']['num'] * $articlesByPage)-1);
		return $results; 
	}

}

