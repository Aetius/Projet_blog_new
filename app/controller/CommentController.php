<?php
namespace App\Controller;

use App\Model\CommentModel;
use Psr\Http\Message\ServerRequestInterface;


class CommentController extends Controller{
	private $modelComment ;


	public function __construct(ServerRequestInterface $request){
		$this->modelComment = new CommentModel();
		parent::__construct($request);
	}


    /**
     * show all comments in the page commentDashboard
     * @return array
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
        return $this->show('dashboardComments', $results);

	}


    /**
     * @return array
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

		return $this->redirectTo("/articles/".$this->modelComment->articleId());
	}


    /**
     * @return array
     */
    public function update(){
        $page = $this->request->getUri()->getPath();
        if (strstr($page, 'articles')){
            $page = substr($page, 0, strpos($page, '/comments'));
        };
		$inputs = $this->modelComment->hydrate($this->request->getParsedBody());

		if ($this->modelComment->prepareUpdate($inputs) == false){
			$_SESSION['success']['2']="Echec de l'enregistrement.";
		}else{
			$_SESSION['success']['1']='Le commentaire a été modifié.';
		}
		return $this->redirectTo("$page");
	}


    /**
     * @return array
     */
    public function delete (){
		$page = $this->request->getUri()->getPath();
		$page = substr($page, 0, strpos($page, '/comments/delete' ));
		$this->modelComment->hydrate($this->request->getParsedBody());
		if ($this->modelComment->delete($this->modelComment->id()) == false){
			$_SESSION['success']['2']="Echec de la suppression.";
		}else{
			$_SESSION['success']['1']='Le commentaire est supprimé.';
		}
		return $this->redirectTo("$page");
	}


}

