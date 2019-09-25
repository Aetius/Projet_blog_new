<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\CommentModel;
use App\controller\UserController; 
use App\controller\ArticleController;


class CommentController extends Controller{
	private $modelComment ; 
	

	public function __construct(){
		$this->modelComment = new CommentModel(); 
		sessionController::getSession();
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

	public function create(){
		$inputs = $this->modelComment->hydrate(); 
		$result= $this->modelComment->createComment($inputs);
		if ($result===true){
			$_SESSION['success']['1']='Le commentaire a été ajouté et est en attente pour modération.';
			header("location:/articles/".$this->modelComment->articleId()); 
		}else{
			$_SESSION['success']['2']="Echec lors de l'ajout du commentaire"; 
			$this->show('createComment'); 
		}

	}

	public function showComments($articleId){ 
		return $this->modelComment->allValidComments($articleId); 
	}

	public function dashboard($idArticle){
		$input['articleId']= $idArticle; 
		$this->modelComment->hydrate($input); 
		return $this->modelComment->allComments();  
	}

	public function manager($idArticle){
		$inputs=$_POST; 
		$inputs['articleId']=$idArticle; 
		$this->modelComment->hydrate($inputs);
		
		if (array_key_exists("delete", $inputs)){
			$this->delete($inputs); 
		}elseif (array_key_exists("Update", $inputs)) {
			header("location:/connexion/update/".$inputs['Update']);
		}else{
			header("location:/connexion/dashboard"); 
		}
	}
	
	public function delete($inputs){
		$delete = $this->modelComment->delete($inputs['delete']);
		if ($delete === true){
			$_SESSION['success']['1']='Le commentaire est supprimé.';
		}else{
			$_SESSION['success']['2']="Echec de la suppression."; 
		}
		$id="/connexion/comment/".$this->modelComment->articleId(); 
		header("location:$id"); 
	}



	public function update(){ 
		$result=$this->modelComment->updateComment(); 
		if ($result===true){
			if ($this->modelComment->published()==1){
				$_SESSION['success']['1']='Le commentaire est publié.';
			}else{
				$_SESSION['success']['1']="Le commentaire n'est plus publié.";
			}
		}else{
			$_SESSION['success']['2']="Echec de l'enregistrement."; 
		}
		$id="/connexion/comment/".$this->modelComment->articleId(); 
		header("location:$id"); 
	}

	public function readComments(){
		return $this->modelComment->read();
	}

	


	private function restrictedAccess(){
		$this->controllerUser = new UserController();
		 sessionController::getSession(); 
		if (!$this->controllerUser->admin()){ 
			return header("location:/connexion");
		}
	}
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


