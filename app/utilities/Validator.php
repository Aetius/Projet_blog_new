<?php

namespace App\utilities;

use App\utilities\ValidatorError; 

class Validator{
	
	private $params;
	private $errors=[];



	public function __construct($params){
		$this->params = $params;
	}

	public function required($keys){                 
		foreach ($keys as $key) {  
			$value=$this->getValue($key); 
			if (is_null($value)){
				 $this->addError($key, 'required');
			}
		}
		return $this; 
	}


	public function name($key){  
		 $value = $this->getValue($key); 
        if(!preg_match("#[^0-9]?[a-zA-Z]{2,}#", $value)){
			$this->addError($key, 'regex');
		}return $this; 
	}

    public function empty($key){ 
         $value = $this->getValue($key); 
         if (isset($value)){
            $this->addError($key, 'robot'); 
         }return $this; 
    }


    public function login($key){ 
         $value = $this->getValue($key);
        if(!preg_match("#[a-zA-Z0-9]{2,}#", $value)){
            $this->addError($key, 'regex');
        }return $this; 
    }


	public function email($key){ 
         $value = $this->getValue($key);   
		if (!preg_match("#^[a-zA-Z0-9._-]{2,}@[a-z0-9]{2,20}\.[a-z]{2,6}$#", $value)){
			$this->addError($key, 'regex');
		}return $this;
	}

	public function password($key){  
         $value = $this->getValue($key); 
        if (!preg_match("#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[,?;.:/!%@])(?=\S*[\d])\S*$#", $value)){
			$this->addError($key, 'password'); 
		}return $this;
	}

    public function isBool($key){  
         $value = $this->getValue($key);
         if (!preg_match("#[0|1]#", $value)){
            $this->addError($key, 'bool'); 
        }return $this; 
    }

   

    public function isInt($key){ 
         $value = $this->getValue($key); 
         if (!is_numeric($value)){
            $this->addError($key, 'number'); 
         }
         return $this; 
    }


	public function notEmpty($keys){  
        foreach ($keys as $key) { 
            $value = $this->getValue($key); 
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty'); 
            }
        } 
        return $this;
    }

    public function length($key, $min, $max = null){ 
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


    public function dateTime ($key, $format = "Y-m-d H:i:s"){
        $value = $this->getValue($key);
        $date = \DateTime::createFromFormat($format, $value);
        $errors = \DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    public function isValid(){
        return empty($this->errors);
    }

  
    public function getErrors(){
        return $this->errors;
    }

    
    private function addError($key, $rule, $attributes = []){
        $this->errors[] = (new ValidatorError($key, $rule, $attributes))->errorMessage();
    }


    private function getValue($key){ 
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        return null;
    }
}






