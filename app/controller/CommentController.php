<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\CommentModel;


class CommentController extends Controller{
	private $modelComment ; 
	

	public function __construct(){
		$this->modelComment = new CommentModel(); 
		parent::__construct(); 
	}
		


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


}

