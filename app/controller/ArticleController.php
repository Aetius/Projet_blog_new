<?php
namespace App\controller; 

use App\Model\ArticleModel;
use Psr\Http\Message\ServerRequestInterface;


class ArticleController extends Controller{
	private $modelArticles;
	

	public function __construct(ServerRequestInterface $request){
		$this->modelArticles = new ArticleModel(); 
		parent::__construct($request);
	}


	public function showHome(){
	    $this->show("/home");
    }

	/**
	 *Show all articles
	 *@param int $idPage
	 */
	public function showAll($idPage){
		$input['num']=$idPage; 
		$page = $this->modelArticles->hydrate($input);  	
		$results['articles'] = $this->allArticlesPublished(); 
		
		$verifyPage = $this->modelArticles->verifyPageNumber($results, $page);//var_dump($verifyPage); die();
		if ( $verifyPage != null){
			header("location:/articles/page/$verifyPage");
		};

		$results = $this->modelArticles->articleDisplay($results, $page); 
		$this->show("/public/dashboard", $results);
	}

	/**
	 *Show one article by id
	 *If id is not in db, return to page all articles
	 *@param int $id
	 */
	public function showOne($id){ 
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			header("location:/articles");
		}else{
			$articles= $this->allArticlesPublished(); 
			$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
			$this->show("/createComment", $results, $articles);
		};
	}

	/**
	 *Show admin dashboard
	*/
	public function showDashboard(){
		$results['articles']=$this->allArticles();
		$results['comments']=$this->allComments(); 
		$this->show('/admin/dashboard', $results);
	}


    /**
     * Show all articles in admin
     * @param $idPage
     * @param array $errors
     */
    public function showAllAdmin($idPage, $errors=[]){
		$input['num']=$idPage; 
		$page = $this->modelArticles->hydrate($input); 
		$preparation['articles']=$this->allArticles();

		$verifyPage = $this->modelArticles->verifyPageNumber($preparation, $page);
		if ( $verifyPage != null){
			header("location:/admin/articles/page/$verifyPage");
		};

		$results = $this->modelArticles->articleDisplay($preparation, $page); 
		$results['comments']=$this->allComments();  
		$results['errors']=$errors;
		$this->show('/admin/articles', $results);
	}

	/**
	 *Show one article with comments in admin
	 *@param string $id
	 *@param array $inputsError
	 */
	public function showOneAdmin($id, $inputsError=[]){
		$results['article']= $this->oneArticle($id);  
		if (!$results['article']){
			return header("location:/admin/articles");
		}
		$articles= $this->allArticles();  
		$results["comments"] = $this->commentsByArticle($this->modelArticles->id());
		$results = $results + $inputsError;
		$this->show('/admin/article', $results, $articles);
	}

	/**
	 *Page create article
	 */
	public function showCreate(){
		$this->show("/admin/create");
	}

	/**
	 *Creation article
	 */
	public function create(){
        $inputs = $this->request->getParsedBody();;
        $inputs['authorId']= $_SESSION['user']['id'];
		$this->modelArticles->hydrate($inputs); 
		
		if ($this->modelArticles->prepareCreate($inputs) == false){
			$_SESSION["success"][2]="Impossible de créer l'article";
			return $this->show('/admin/create', $this->modelArticles->saveInputs());	
		}
		$_SESSION["success"][1]= "L'article est créé";
		return header("location:/admin/dashboard");
	}

	/**
	 *Verify if article and its comments are delete
	 */
	public function delete(){
		$this->modelArticles->hydrate($this->request->getParsedBody());
		$id = $this->modelArticles->id();
		if ( $id == null){
			$_SESSION['success'][2]="Echec de la suppression!!";
			return header("location:/admin/articles");
		};
		$modelComment = $this->factory->getModel('comment');
		if ($modelComment->delete($id, 'article_id') == false){
			$_SESSION['success'][2]="Echec de la suppression des commentaires !!";
			return header("location:/admin/articles/$id");
		}; 
		if ($this->modelArticles->delete($id) == false){
			$_SESSION['success'][2]="Echec de la suppression de l'article !!";
			return header("location:/admin/articles/$id");
		}; 
		$_SESSION['success'][1]="L'article et les commentaires associés ont bien été supprimés.";
		header("location:/admin/articles");
	}


	/**
	 *Update page with all articles. Can update published and date publication
	 *@param string $id
	 */
	public function updatePublication($id){   
		$inputs = $this->request->getParsedBody();
		if ($id == null){
			$inputs['page']=1; 
		}else{
			$inputs['page']=intval($id); 
		} 
		if ($this->update($inputs, 'prepareUpdatePublished')==false){
			$results= $this->modelArticles->errors();
			return $this->showAllAdmin($inputs['page'], $results); 
		}; 
		return $this->showAllAdmin($inputs['page']);
	}

	/**
	 *Update article
	 *@param int $idArticle
	 */
	public function updateArticle($idArticle){ 
		$inputs = $this->request->getParsedBody();
		$inputs["id"]=$idArticle; 
		$this->update($inputs, "prepareUpdate"); 
		return header("location:/admin/articles");
	}

	/**
	 *Execute update
	 *@param array $inputsVerify
	 *@param string $method
	 *@return bool
     */
	private function update($inputsVerify, $method){ 
		$inputs= $this->modelArticles->hydrate($inputsVerify); 
		
		if ($this->modelArticles->$method($inputs) == false){ 
			$_SESSION["success"][2]="La mise à jour de l'article a échoué"; 
			return false; 
		}
			$_SESSION["success"][1]="Mise à jour de l'article effectuée";
			return true; 
	}

	/*private function update($inputsVerify, $method){ 
		$inputs= $this->modelArticles->hydrate($inputsVerify); 
		$result = $this->modelArticles->$method($inputs); 

		if (is_null($result)){ 
			$_SESSION["success"][1]="Mise à jour de l'article effectuée";
			return $result; 
		}
		$_SESSION["success"][2]="La mise à jour de l'article a échoué"; 
		$result['errors']= $this->modelArticles->errors(); 
		return $this->showOneAdmin($result['inputsError']['id'], $result); 
		
	}*/
		
		



	/**
	 *Find all articles in db, and add the user
	 *@return array
	 */
	private function allArticles(){
		$allArticles=$this->defineAllUsers( (array)$this -> modelArticles -> all() );
		return $allArticles; 
	}

	/**
	 *Find all articles published in db, and add the user
	 *@return array
	 */
	private function allArticlesPublished(){
		$date = $this->modelArticles->setDate();
		$search =[
            "0"=>array(
                "field"=>"date_publication",
                "value"=> $date,
                "operator"=>"<"
            ),
            "1"=>array(
                "field"=>"publicated",
                "value"=>"1",
                "operator"=>"="
            )];
		$allArticles=$this->defineAllUsers($this->modelArticles->search2($search));  
		return $allArticles; 
	}

	/**
	 *Find one article by id in db, and add the user
	 *@return array
	 */
	private function oneArticle($id){ 
		$article['id']=$id; 
		$this->modelArticles->hydrate($article); 
		$article = $this->modelArticles->one('id', $this->modelArticles->id());
		if ($article){
			$article['author'] = $this->defineUser($article["author_id"]);
			return $article; 
		}
	}

	/**
	 *Find all comments in db
	 *@return array
	 */
	private function allComments(){
		$modelComment = $this->factory->getModel('Comment'); 
		return $modelComment->all();
	}

	/**
	 *Find all comments by article id
	 *@return array
	 */
	private function commentsByArticle($idArticle){
		$modelComment = $this->factory->getModel('Comment');
        $search =[
            "0"=>array(
                "field"=>"article_id",
                "value"=> $idArticle,
                "operator"=>"="
            )];
        return $modelComment->search2($search);
        //return $modelComment->search( "article_id", $idArticle);
	}

	/**
	 *For each article, add name of user
	 *@param array $articles
	 *@return array
	 */
	private function defineAllUsers($articles){ 
		foreach ($articles as $key => $value) {    
			$user = $this->defineUser($value["author_id"]);
			$articles[$key]["author_name"]= $user; 			
		}  
		return $articles;
	}

	/**
	 *Find user by id in db
	 *@param int $userId
	 *@return string
	 */
	private function defineUser($userId){ 
		$modelUser = $this->factory->getModel('User'); 
		$userParams = $modelUser->one('id', $userId); 
		$user=$userParams['login']; 
		return $user; 
	}




}

