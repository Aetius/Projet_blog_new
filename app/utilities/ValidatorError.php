<?php

namespace App\Utilities;


class ValidatorError{
	private $key; 
	private $rule; 
	private $attributes; 

	private $messages = [
		'required' => 'Le champ %s est requis.',
        'empty' => 'Le champ %s ne peut être vide.',
        'slug' => 'Le champ %s n\'est pas un slug valide.',
        'minLength' => 'Le champ %s doit contenir plus de %d caractères.',
        'maxLength' => 'Le champ %s doit contenir moins de %d caractères.',
        'betweenLength' => 'Le champ %s doit contenir entre %d et %d caractères.',
        'datetime' => 'Le champ %s doit être une date valide (%s).',
        'regex'=>'Le champ %s est incorrect.', 
        'number'=>'Le champ %s doit être un nombre.',
        'robot'=>'Je suis un robot', 
        'bool'=>'Le champ %s doit être un booléen.',
        'password' => "Le champ %s doit contenir 8 caractères, 1 lettre, 1 chiffre et un des caractères spéciaux (,?;.:/!@')."
	]; 

	public function __construct($key, $rule, $attributes=[]){
		$this->key= $this->traduction($key);  
		$this->rule=$rule; 
		$this->attributes=$attributes; 
	}

	public function errorMessage(){
		$params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes); 
		return call_user_func_array('sprintf', $params); 
	}


	private function traduction($key){
		switch ($key) {
			case 'title':
				return 'titre';
			case 'name':
				return'nom';
			case 'lastname':
				return 'prénom';
			case 'content':
				return 'contenu';
			case 'description':
				return 'description';
			case 'email':
				return 'mail'; 
			case 'login' : 
				return 'login';
			case 'password' : 
				return 'mot de passe';
			case 'passwordConfirm' : 
				return 'confirmation du mot de passe'; 
			case 'is_admin':
				return 'administrateur'; 
			case 'activate':
				return 'actif'; 
			case 'published':
				return '"publier"';
			case 'author'; 
				return 'auteur'; 
			default: 
				return $key;
		}
	}
}