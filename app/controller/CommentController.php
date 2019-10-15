<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\CommentModel;
/*use App\controller\UserController; 
use App\Model\ArticleModel;*/
 


class CommentController extends Controller{
	private $modelComment ; 
	

	public function __construct(){
		$this->modelComment = new CommentModel(); 
		//$this->modelArticle = new ArticleModel(); 
		parent::__construct(); 
	}
		
		/*$this->modelComment = new CommentModel(); 
		$this->controllerArticle = new ArticleController(); 
		*/

	/*public function show(){
		$this->restrictedAccess();
		$this->modelComment = new CommentModel(); 
		$this->controllerArticle = new ArticleController(); 
		$postValue = $this->modelComment->hydrate(); 
		
		$this->controllerArticle->showOneArticle($idArticle,'createComment');

	
	}*/




	

/*	public function showComments($articleId){ var_dump($this->modelComment->search('id', $articleId)); die(); 
		return $this->modelComment->search('id', $articleId); 
	}*/



	public function showDashboard(){
		$comments = $this->modelComment->all();
		$modelArticle = $this->factory->getModel('Article'); 
		foreach ($comments as $key => $value) {
			$results= $modelArticle->one('id', $comments[$key]['article_id']); 
			$comments[$key]['article_title'] = $results['title']; 
		}
		
		$results['comments']= $comments; 
		$this->show('dashboardComments', $results); 
	}




	public function dashboard($idArticle){
		$input['articleId']= $idArticle; 
		$this->modelComment->hydrate($input); 
		return $this->modelComment->all();  
	}


	/*public function managerArticlePage($idArticle){
		$inputsVerif=$_POST; 
		$inputsVerif['articleId']=$idArticle; 
		$inputs = $this->manager($inputsVerif); 
		
		$page = $inputs['articleId'];  
		header("location:/admin/articles/$page");
	}


	public function managerCommentsPage(){
		$inputsVerif=$_POST; 
		$this->manager($inputsVerif); 

		header("location:/admin/comments"); 
	}


	public function manager($inputsVerif){  var_dump("expression"); die(); 
		$inputs = $this->modelComment->hydrate($inputsVerif);
		
		if (array_key_exists("delete", $inputs)){
			 $this->delete($inputs); 
		}elseif (array_key_exists("update", $inputs)) {  
			 $this->update();			
		};
		return $inputs; 
	}*/

	public function create(){ 
		$inputs = $this->modelComment->hydrate($_POST); 
		$results= $this->modelComment->prepareCreate($inputs); 
		if ($results == null){ 
			$_SESSION['success']['1']='Le commentaire a été ajouté et est en attente pour modération.';
		}else{
			$_SESSION['success']['2']="Echec lors de l'ajout du commentaire"; 
			$_SESSION['errors']=$results['errors']; 
		}
		header("location:/articles/".$this->modelComment->articleId()); 
		
	}

	public function update(){  
		$page = $_SERVER['HTTP_REFERER'];
		$inputs = $this->modelComment->hydrate($_POST); 

		$result=$this->modelComment->prepareUpdate($inputs); 
		if ($result== null){
			$_SESSION['success']['1']='Le commentaire a été modifié.';	
		}else{
			$_SESSION['success']['2']="Echec de l'enregistrement."; 
		}
		header("location:$page"); 
	}


	/**
	*array @inputs
	*
	*/

	public function delete (){ 
		$page = $_SERVER['HTTP_REFERER'];
		$inputs = $this->modelComment->hydrate($_POST);
		$result = $this->modelComment->delete($inputs['id']); 
		if ( $result == null){
			$_SESSION['success']['1']='Le commentaire est supprimé.';
		}else{
			$_SESSION['success']['2']="Echec de la suppression."; 
		}
		header("location:$page"); 
	}


	/*public function deleteComments(){
		$postValue = $this->modelComment->hydrate(); 
		if (!is_null(intval($postValue['Delete']))){
			return $this->modelComment->delete($postValue['Delete'], "article_id");
		}; 
	}
*/


	



/*	public function readComments(){
		return $this->modelComment->read();
	}*/

	

/*
	private function restrictedAccess(){
		$this->controllerUser = new UserController();
		 sessionController::getSession(); 
		if (!$this->controllerUser->admin()){ 
			return header("location:/connexion");
		}
	}*/
}
/*
les commentaires sont affichés avec les articles : 
	dans le dashboard
	dans les articles sur la page de blog. 

	le controller des articles fait la demande.
	ce controller transmet la demande au model. 
	le modèle fait la requete, et récupère les comments liés à l'article. 

	on fait


les commentaires sont crées (avec l'affichage des articles)

les commentaires peuvent être supprimés par le créateur ou l'admin. 

les commentaires peuvent être modifiés par le créateur 

cas de l'article supprimé : les commentaires sont automatiquement supprimés. 

*/


