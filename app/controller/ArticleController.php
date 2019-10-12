<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\ArticleModel;





class ArticleController extends Controller{
	private $modelArticles;
	//private $modelUser;
	//private $modelComment; 
 


	public function __construct(){
		$this->modelArticles = new ArticleModel(); 
		//$this->modelUser = new UserModel(); 
		/*$this->modelComment = new CommentModel(); */
		parent::__construct(); 
		
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
		//$pagination = $this->definePagination($result, $id);
		//$showResult = $this->defineArticlesShow($result, $pagination); var_dump($showResult); die(); 
		$this->show('/admin/dashboard', $results);
	}

	public function showAllAdmin($idPage){   
		$results['articles']=$this->allArticles();
		$results = $this->articleDisplay($results, $idPage); 
		$results['comments']=$this->allComments(); 
		$this->show('/admin/articles', $results);
	}


	public function showOneAdmin($id){
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			header("location:/admin/articles");
		}else{
			$articles= $this->allArticles();  
			$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
			$this->show("/admin/article", $results, $articles);
		};
		
	}

		
	public function showCreate(){
		$this->show("/admin/create");
	}

	/*public function showUpdate($idArticle){  
		$results = $this->oneArticlePage($idArticle); 
		$this->show("/admin/create",$results);
	}*/

/*	public function showCreateComment($idArticle){
		$results = $this->oneArticlePage($idArticle);
		$this->show('createComment', $results);
	}*/



	/*private function articles($id){
		$articles["oneArticle"] =  $this-> controllerArticle->oneArticlePage($id);
		$articles["allArticles"] = $this-> controllerArticle->allArticles(); 
		
			return $articles;
		}
	}*/




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
		return $modelComment->allComments();
	}

	private function commentsByArticle($idArticle){
		$modelComment = $this->factory->getModel('Comment');  
		return $modelComment->search( "article_id", $idArticle); 		
	}


	private function defineAllUsers($articles){ 
		//$comments = $this->modelComment->allComments($published, $idArticle, $request); 
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

/*	private function definePagination($result, $id){
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
*/



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
	/*	if (!isset($_SESSION['user']['id']){
			$userModel = $this->factory->getModel('user');
			$user["authorId"] = $userModel->one($_SESSION['access']['login'], 'login'); 
		}else{
			$user["authorId"]=$_SESSION['user']['id'];
		}

			
		$this->modelArticles->hydrate($user);*/ 
		$inputs = $this->modelArticles->hydrate($_POST); 
		//$this->modelArticles->validation($inputs); 
		$result = $this->modelArticles->createArticle($inputs);
		if ($result===true){
			$_SESSION["success"][1]= "L'article est crée";
			header("location:/admin/dashboard");
		}else{
			$_SESSION["success"][2]="Impossible de créer l'article";
			$this->show('/admin/create', $result);	
		}
	}

	public function articlesPage($idPage){
		return $this->showAll($this->articleDisplay($idPage)); 
	}

	public function updatePublication(){
		
		/*if (isset($_POST['page'])){
			return $this->showAllAdmin($this->articleDisplay());  */
		if (isset($_POST['published'])){
			$this->update($_POST, 'updatePublished'); 
		}else{
			header("location:/admin/articles");
		}
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


	/*private function articleDisplay($idPage){  var_dump($id); die(); 
		$articlesByPage = 10; 
		$results = []; 
		$inputs = $this->modelArticles->hydrate($_POST);
		
		
		if (!isset($inputs['pageNumber']) || (!is_numeric($inputs['pageNumber']))) {
			$inputs['pageNumber'] = 1; 
		}
		if ($inputs['page'] === 'Page précédente'){
			if ($inputs['pageNumber'] > 1) {
				$results['page']= $inputs['pageNumber'] - 1; 
			}else{
				$results['page'] = $inputs['pageNumber'];
			}
		}elseif ($inputs['page'] === 'Page suivante'){
			$lenArticles = count($this->allArticles());  
			if ($inputs['pageNumber']*$articlesByPage < ($lenArticles - $articlesByPage0)) {
				$results['page']= $inputs['pageNumber'] + 1; 
			}else {
				$results['page'] = $inputs['pageNumber']; 
			}
		}
		
		return ($results); 
	}*/

	

/*
	public function updatePublished(){
		$inputs = $_POST; 
		$this->update($inputs, __function__); 
	}*/


	public function updateArticle($idArticle){ 
		$inputs = $_POST;
		$inputs["id"]=$idArticle; 
		
		$this->update($inputs, __function__); 
	}


	private function update($inputsVerif, $method){ 
		$inputs= $this->modelArticles->hydrate($inputsVerif); 
		$result = $this->modelArticles->$method($inputs); 

		if ($result===true){  
			$_SESSION["success"][1]="Mise à jour de l'article effectuée";
			header("location:/admin/articles");
		}else{
			$_SESSION["success"][2]="La mise à jour de l'article a échoué";
			$result['errors']= $this->modelArticles->errors(); 
			$this->showAllAdmin($result);
		}
	}


	public function managerArticle($id){
 
		if (isset($_POST['delete'])){
			$this->delete(); 
		}elseif (isset($_POST['update'])) {
			$this->updateArticle($id); 
		}elseif (isset($_POST['updateComment'])) {

			# code...
		}
	}


	private function delete(){
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

