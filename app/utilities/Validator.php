<?php

namespace App\utilities;

use App\utilities\ValidatorError; 

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

	public function required($keys){                   ///keys est un tableau, contenant clé et valeur. à rapprocher de l'hydratation. 
		foreach ($keys as $key) {  
			$value=$this->getValue($key); 
			if (is_null($value)){
				 $this->addError($key, 'required');
			}
		}
		return $this; ///sert à retourner l'objet pour continuer les tests. 
	}


	public function name($key){
		 $value = $this->getValue($key);
        if(!preg_match("#[a-zA-Z0-9]{2,}#", $value)){
			$this->addError($key, 'regex');
		}return $this; 
	}

	public function mail($key){
         $value = $this->getValue($key);
		if (preg_match("#^[a-zA-Z0-9._-]{2,}@[a-z0-9]{2,20}\.[a-z]{2,6}$#", $key)){
			$this->addError($key, 'regex');

		}return $this;
	}

	public function password($key){ 
         $value = $this->getValue($key);
        if (!preg_match("#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[,?;.:/!%@])(?=\S*[\d])\S*$#", $value)){
			$this->addError($key, 'password'); 
		}return $this;
	}


	public function notEmpty($keys) {
        foreach ($keys as $key) { 
            $value = $this->getValue($key); 
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty'); 
            }
        } 
        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null): self{ 
        $value = $this->getValue($key); 
        $length = mb_strlen($value);
        if (
            !is_null($min) &&
            !is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        }
        if (
            !is_null($min) &&
            $length < $min
        ) {
            $this->addError($key, 'minLength', [$min]);  
            return $this;
        }
        if (
            !is_null($max) &&
            $length > $max
        ) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    /**
     * Vérifie que l'élément est un slug
     *
     * @param string $key
     * @return Validator
     */
    public function slug(string $key): self
    {
        $value = $this->getValue($key);
        $pattern = '/^([a-z0-9]+-?)+$/';
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    public function dateTime (string $key, string $format = "Y-m-d H:i:s"): self
    {
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    /**
     * Récupère les erreurs
     * @return ValidationError[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Ajoute une erreur
     *
     * @param string $key
     * @param string $rule
     * @param array $attributes
     */
    private function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errors[] = (new ValidatorError($key, $rule, $attributes))->errorMessage();
        //echo $this->errors[$key];die(); pour pouvoir lire les données. __toString convertit l'objet en chaine, et met les params. 
    }

    private function getValue(string $key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}






