<?php
namespace App\controller; 

use App\controller\Controller;
use App\Model\CommentModel;
use Psr\Http\Message\ServerRequestInterface;


class CommentController extends Controller{
	private $modelComment ; 
	

	public function __construct(ServerRequestInterface $request){
		$this->modelComment = new CommentModel(); 
		parent::__construct($request);
	}
	
	/**
	 *Show all articles in the db
	 *@return page
	 */	
	public function showDashboard(){
		$comments = $this->modelComment->all();
		$modelArticle = $this->factory->getModel('Article');
		$countComments = count($comments);
		for ($key = 0; $key<$countComments; $key++){
            $results= $modelArticle->one('id', $comments[$key]['article_id']);
            $comments[$key]['article_title'] = $results['title'];
        }
        $results['comments']= $comments;
        $this->show('dashboardComments', $results);

        /*foreach ($comments as $key => $value) {
			$results= $modelArticle->one('id', $comments[$key]['article_id']); 
			$comments[$key]['article_title'] = $results['title']; 
		}
		$results['comments']= $comments; 
		$this->show('dashboardComments', $results); */
	}


	/**
	 *Create a new comment
	 *@return page
	 */
	public function create(){ 
		$inputs = $this->modelComment->hydrate($this->request->getParsedBody());
		
		if ($this->modelComment->prepareCreate($inputs) == false){
			$_SESSION["success"][2]="Impossible de créer l'article";
			$errors = $this->modelComment->errors(); 
			$_SESSION['errors']= $errors['errors'];  
		}else{
			$_SESSION['success']['1']='Le commentaire a été ajouté et est en attente pour modération.';
		}

		header("location:/articles/".$this->modelComment->articleId()); 
	}

	/**
	 *Update comments
	 *@return page
	 */
	public function update(){  
		$page = $_SERVER['HTTP_REFERER'];
		$inputs = $this->modelComment->hydrate($this->request->getParsedBody());

		if ($this->modelComment->prepareUpdate($inputs) == false){
			$_SESSION['success']['2']="Echec de l'enregistrement."; 
		}else{
			$_SESSION['success']['1']='Le commentaire a été modifié.';	
		}
		header("location:$page"); 
	}

	/**
	 *Delete commentaire
	 *@return page 
	 */
	public function delete (){ 
		$page = $_SERVER['HTTP_REFERER'];
		$inputs = $this->modelComment->hydrate($this->request->getParsedBody());
		if ($this->modelComment->delete($this->modelComment->id()) == false){
			$_SESSION['success']['2']="Echec de la suppression."; 
		}else{
			$_SESSION['success']['1']='Le commentaire est supprimé.';
		}
		header("location:$page"); 
	}


}

