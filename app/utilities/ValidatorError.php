<?php

namespace App\utilities;


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
        'password' => "Le champ %s doit contenir 8 caractères, 1 lettre, 1 chiffre et un des caractères spéciaux (,?;.:/!@')."
	]; 

	public function __construct($key, $rule, $attributes=[]){
		$this->key= $this->traduction($key);  
		$this->rule=$rule; 
		$this->attributes=$attributes; 
	}

	public function errorMessage(){
		$params = array_merge([$this->messages[$this->rule], $this->key], $this->attributes); 
		//return (string)call_user_func_array('sprintf', $params);
		//var_dump($params); die(); 
		return call_user_func_array('sprintf', $params); 
	}


	private function traduction($key){
		switch ($key) {
			case 'Title':
				return 'titre';
			case 'Name':
				return'nom';
			case 'Lastname':
				return 'prénom';
			case 'Content':
				return 'contenu';
			case 'Description':
				return 'description';
			case 'Mail':
				return 'mail'; 
			case 'Login' : 
				return 'login';
			case 'Password' : 
				return 'mot de passe';
			case 'PasswordConfirm' : 
				return 'confirmation du mot de passe'; 
			default: 
				return $key;
		}
	}
}