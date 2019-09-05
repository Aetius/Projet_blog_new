<?php

namespace utilities;

class Validator{
	
	/**
	*@var array
	*/
	private $params;

	/**
	*@var string[]
	*/
	private $errors=[];



	public function __construct($params){
		$this->params = $params; 
	}

	public function require($keys){
		foreach ($keys as $key) {
			$value=$this->getValues($key); 
			if (is_null($value)){
				$this->errors[$key]="Le champs $key est vide";
			}
		}
		return $this; 
	}

	public function length($key, $min, $max=null){
		$value=$this->getValue($key); 
		$length=mb_strlen($value); 

		if(
			!is_null($min) &&
			!is_null($max) &&
			($length < $min || $length > $max)
		)	{
				$this->errors[$key]="pb entre min et max";
				return $this; 
		}
		if(
			!is_null($min) &&
			($length < $min)
		)	{
				$this->errors[$key]="pb entre min et max";
				return $this; 
		}

		if(
			!is_null($max) &&
			($length > $max)
		)	{
				$this->errors[$key]="pb max";
				return $this; 
		}
		return $this; 
	}

	public function notEmpty($keys){
		foreach ($keys as $key) {
			$value=$thisè>getValues($key); 
			if (is_null($value)|| empty($value)){
				$this->errors[$key]="min est pb";
			}
		}


	public function isValid(){
		return empty($this->errors); 
	}

	public function getErrors(){
		return $this->errors; 
	}

	public function datetime($key){

		$value=$this->getValue($key);
		$datetime=\date_create_from_format('Y-m-d H:i:s', $value)
		$errors=\date_get_last_errors();
		if ($errors['error_count'] > 0){    /////attention, risque d'erreurs avec error_count (en lien avec les tests unitaires. 38min dans la vidéo.)
			$this->errors[$key]='pas de propriété datetime';
		}
		return $this
	}

	private function getValue($key){
		if (array_key_exists($key, $this->params)){
			return $this->params[$key];
		}
		return null; 
	}
}


/*validator*/
public $errors=[];


	public static function name($name){
		if(preg_match("#^[a-zA-Z]{2,}$#", $name)){
			return true;
		}
		return false;
	}

	public static function mail($mail){
		if (preg_match("#^[a-zA-Z0-9\.]{2,}[@]{1}[a-z]{1,20}\.[a-z]{2,8}$#", $mail)){
			return true;
		}
		return false;
	}

	public static function password($password){
		if (preg_match("#^[a-zA-Z(0-9)+(,?.;!%@&)+]{8,}$#", $password)){
			return true;
		}
		return false;
	}






dans model : 
$validator = $this->getValidator($request); 
if ($validator->isValid()){
	//on met dans la partie create ou update.
}
$errors=validator->getErrors();
//on renvoie à la vue. 

private function getValidator($request){
	return (new Validator($request->getParsedBody()))
	->required('content', 'name')
	->length('content', 10)
	->length('name', 2, 250);

	//règle de validation faite. 
	//on l'envoie à la vue. 
}