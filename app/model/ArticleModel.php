<?php

namespace App\Model; 


use App\utilities\Validator;
use App\model\AppModel; 
use App\utilities\AppFactory;




class ArticleModel extends AppModel{

	private $description;
	private $dateCreation;
	private $content;
	private $published=null; 
	private $publishedDate=null; 
	private $title; 
	private $authorId; 
	private $articlesByPage = 10; 

	/**
	 *Decode articles with htmlspecialchars
	 *@return array 
	 */
	public function allArticles(){
		$articles = $this->all(); 
		foreach ($articles as $key => $value) {
			if (array_key_exists("content",$value)){
				($articles[$key]['content'] = htmlspecialchars_decode($value['content'])); 
			}
		}
		return $articles; 
	}

	/**
	 *Prepare datas before update of the article
	 *@param array $inputs
	 *@return bool 
	 */
	public function prepareUpdatePublished($inputs){
		if ((!isset ($inputs['published']))|| (!isset ($this->id))){
			$this->errors[]="Erreur lors de l'enregistrement";
			return false; 
		}
		
		$validationInput['isBool']['published']=$inputs['published']; 
		$this->validation($validationInput, 'getValidatorUpdate'); 

		if (!empty($this->errors)){
			return false;
		};
		$fields=array(
			'publicated'=>$this->published,
			'date_publication'=>$this->publishedDate
		);
		return $this->recordValid('update', $fields);
	}

	/**
	 *Prepare datas before update of the article
	 *@param array $inputs
	 *@return bool 
	 */
	public function prepareUpdate($inputs){ 
		$this->validation($inputs);
		$article=$this->one('title', $this->title);  
		if (((($article['title'])===($this->title))&&($article['id']!==$this->id)) && ($this->title != null)){ 
			array_push($this->errors, "Le titre de cet article existe déjà!");
		};
 
		if (!empty($this->errors)){
			$this->saveInputs = $this->isErrors($inputs); 
			return false; 
		};

		$fields=array(
			'title'=>$this->title, 
			'content'=>$this->content, 
			'date_update'=>$this->setDate(), 
			'description'=>$this->description, 
			'publicated'=>$this->published,
			'date_publication'=>$this->publishedDate
		);
		return $this->recordValid('update', $fields);
	}

	/**
	 *Prepare datas before creation of the article
	 *@param array $inputs
	 *@return bool 
	 */
	public function prepareCreate($inputs){ 	
		$this->validation($inputs);
		$article=$this->one('title',$this->title);  
		
		if ((($article['title'])===($this->title))&& ($this->title != null)){ 
			$this->errors[]="Le titre de cet article existe déjà!";
		};

		if (!empty($this->errors)){
			$this->saveInputs = $this->isErrors($inputs); 
			return false; 
		};

		$fields = array(
			'title'=>($this->title), 
			'description'=>($this->description),
			'content'=>($this->content),
			'publicated'=>($this->published),
			'date_publication'=>($this->publishedDate), 
			'date_creation'=>($this->setDate()),
			'author_id'=>($this->authorId)
		);
		return $this->recordValid('create', $fields);
	}

	/**
	 *Define which articles will be display in the page
	 *Verify if the page number exists. If not, redirection to the highest page number. 
	 *@param array $resultsArticles
	 *@param array $idPage
	 *@return array
	 */
	public function articleDisplay($resultsArticles, $idPage){
		$resultsArticles['page'] = $idPage;

		if ((!isset($resultsArticles['page']['num'])) || (empty($resultsArticles['page']['num'])) || ($resultsArticles['page']['num'] < 1 )){
			$resultsArticles['page']['num'] = 1; 
		}; 
	
		$resultsArticles['page']['min'] = ($resultsArticles['page']['num'] -1) * $this->articlesByPage;
		$resultsArticles['page']['max'] = (($resultsArticles['page']['num'] * $this->articlesByPage)-1);
		return $resultsArticles; 
	}

    /**
     *Verify the page number
     * @param array $resultsArticles
     * @param $idPage
     * @return int
     */
	public function verifyPageNumber($resultsArticles, $idPage){
		$lenArticles = count($resultsArticles['articles']); 
		if ((($lenArticles/$this->articlesByPage)+1) <= $idPage['num']){
			$page = ceil($lenArticles/$this->articlesByPage); 
			return $page; 
		} 
	}

	/**
	 *Getters
	 */
	public function errors(){
		return $this->errors;
	}

	public function title(){
		return $this->title; 
	}

	public function description(){
		return $this->description; 
	}

	public function content(){
		return $this->content; 
	}

	public function id(){ 
		return $this->id; 
	}



	/**
	 *Setters
	 */
    /**
     * @return string
     */
	public function setDate(){
		$datetime = getdate(); 
		$date = $datetime['year']."-".$datetime['mon']."-".($datetime['mday']); 
		return $date;
	}

    /**
     * @param $input
     * @return string
     */
    public function setTitle($input){
		return $this->title = $input;
	}

    /**
     * @param $name
     * @return string
     */
	public function setDescription($name){
		return $this->description=$name; 
	}

    /**
     * @param $name
     * @return string
     */
	public function setContent($name){
		return $this->content=$name; 

	}

    /**
     * @param $name
     * @return string
     */
	public function setPublished($name){
		return $this->published=$name; 
	}

    /**
     * @param $name
     * @return string|NULL
     */
	public function setPublishedDate($name){
		$nameVerify=explode("-", $name);
		if (checkdate($nameVerify["1"], $nameVerify["2"], $nameVerify["0"])){
			if ($this->published === "1"){ 
				return $this->publishedDate = $name; 
			}
        }
		return $this->publishedDate =NULL;
	}

    /**
     * @param $name
     * @return string|null
     */
    public function setId($name){
		if ($this->one( 'id', $name)){
			return $this->id = $name; 
		}
		return $this->id = NULL;
	}

    /**
     * @param $name
     * @return string
     */
	public function setAuthorId($name){ 
			return $this->authorId = $name;
	}


	/**
	 *Validator verification
	 *@return object
	 */
	protected function getValidator($inputs){		
		return (new Validator($inputs))
			->length('content', 1)
			->length('description', 1)
			->length('title', 1)
			->name('title');
	}

}