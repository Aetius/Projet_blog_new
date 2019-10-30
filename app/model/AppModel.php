<?php

namespace App\Model; 


use \PDO; 
use App\utilities\Purifier;
use App\utilities\Validator;

class AppModel{
	protected $errors=[]; 
	protected $id;
	protected $db;
	protected $table; 


	public function __construct (){
		$this->db=(BDDConnection::connection());
		if ($this->table === null){
			$parts= explode("\\", get_class($this));
			$this->table = strtolower(str_replace("Model", "", end($parts)))."s";
		}
	}


	public function hydrate($verifyInputs=[]){
		$inputs=[]; 
		
		foreach ($verifyInputs as $key => $value) {
			$key = Purifier::htmlPurifier($key);
			$value=Purifier::htmlPurifier($value);
			
			$function = "set".$key;
			$inputs+= [$key=>$value];
			 
			if(method_exists(get_class($this), $function)){
				$this->$function($value); 
			} 
		} 
		return $inputs; 
	}
	


/**
*prepare requests
*/
	public function create($fields){ 
		$sqlParams=[]; 
		$attributes=[];
		foreach ($fields as $key => $value) {
			$sqlParams[]="$key=?";
			$attributes[]= $value;
		}
		$sqlParams = implode(',', $sqlParams);
		return $this->executeRequest("INSERT INTO {$this->table} SET $sqlParams", $attributes, true);
	}


	public function all(){ 
		return $this->executeRequest("SELECT * FROM {$this->table} ORDER BY id DESC");
	}


	public function one($fieldName, $field){ 
		return $this->executeRequest("SELECT * FROM {$this->table} WHERE $fieldName=:field", [":field"=>$field], true);
	}


	public function search($fieldName, $field){  
		return $this->executeRequest("SELECT * FROM {$this->table} WHERE $fieldName=:fieldName ORDER BY id DESC", [":fieldName"=>$field]);
	}


	public function update($fields, $id){  
		$sqlParams=[]; 
		$attributes=[];
		foreach ($fields as $key => $value) {
			$sqlParams[]="$key=?";
			$attributes[]= $value;
		}
 		$sqlParams = implode(',', $sqlParams);
 	
 		$attributes[]=$id;
 		return $this->executeRequest("UPDATE {$this->table} SET $sqlParams WHERE id=?", $attributes, true);
	}


	public function delete($id, $field='id'){
		return $this->executeRequest("DELETE FROM {$this->table} WHERE $field=:id", [":id"=>$id], true );
	}



	protected function executeRequest($statement, $attributes=null, $one=false){ 
		if ($attributes){
			$request= $this->db->prepare(
				$statement
			);
		}else{
			$request= $this->db->query(
				$statement 
			);
		}

		$result = $request->execute($attributes);
		if (strpos($statement, 'SELECT') === 0){
			if ($one){
				$result = $request->fetch(PDO::FETCH_ASSOC); ;
			}else{
				$result = $request->fetchAll(PDO::FETCH_ASSOC); 
			}
		};		
		$request->closeCursor(); 
		return $result; 
	
	}


	protected function isErrors($fields){
		if (!empty($this->errors)){
			$result['errors']=$this->errors;
			foreach ($fields as $key => $value) {
				$result['inputsError'][$key]=$value; 
			}
		return $result; 
		} 
	}
	

	protected function recordValid($function, $fields){ 		
		$id=$this->id;
		
		if (($this->$function($fields, $id)!==true)){ 
			$this->errors[]="L'enregistrement a échoué"; 
		} 
	}


/*
*Verify if inputs are expected datas. 
*params in models
*/
	protected function validation($inputs, $validator="getValidator"){
		$validator = $this->$validator($inputs);   
		if ($validator->isValid()!= true){
			return $this->errors =  $validator->getErrors();
		}	
	}

/*
*case update : if there's only few inputs to control
*/
	protected function getValidatorUpdate($inputs){  
		$validationInputs = array_values($inputs); 
		$validator = new Validator($validationInputs['0']);
		
		foreach ($inputs as $key => $value) {
			foreach ($value as $key2 =>$value2 ) { 
				$validator ->$key($key2);
			}
		}
		return $validator;
	}



}
