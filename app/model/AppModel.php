<?php

namespace App\Model; 

use App\Model\Model; 
use \PDO; 
use App\utilities\Purifier;
use App\utilities\Validator;

class AppModel extends Model{
	protected $errors=[]; 
	private $id='';



	public function hydrate($verifyInputs=[]){
		$inputs=[]; 
		
		foreach ($verifyInputs as $key => $value) {
			$key = Purifier::htmlPurifier($key);
			$value=Purifier::htmlPurifier($value);
			
			$function = "set".$key;
			//$input = $value;
			$inputs+= [$key=>$value];
			 
			if(method_exists(get_class($this), $function)){
				$this->$function($value); 
			} 
		} 
		return $inputs; 
	}
	



/*
* Verification of errors' array. If it's not empty, return errors. 
*/
	protected function isErrors($fields){
		if (!empty($this->errors)){
			$result['errors']=$this->errors;
			foreach ($fields as $key => $value) {
				$result['inputsError'][$key]=$value; 
			}
		return $result; 
		} 
	}
	


	/*
	*@id if id needs (update, delete)
	*verify errors
	*launch request 
	*return bool
	**/
	protected function creationSuccess($function, $fields)	{ 
		$this->isErrors($fields); //à supprimer et à mettre dans les fonctions mères.
		/*if (!empty($this->errors)){
			$result['errors']=$this->errors;
			foreach ($fields as $key => $value) {
				$result[$key]=$value; 
			}var_dump($result); die();
			return $result; 
		}*/
		if (method_exists($this, 'id')){
			$id=$this->id();
		}/*else{
			$id =""; à voir si fonctionne. 
		}   */

		
		if (($this->$function($fields, $id)!==true)){ 
			$this->errors[]="L'enregistrement a échoué"; 
			$result['errors']=$this->errors;
			return $result; 
		
		}
	}


/*
*Verify if inputs are expected datas. 
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
		foreach ($inputs as $key => $value) {
			foreach ($value as $key2 =>$value2 ) {
				return(new Validator($value))
				->$key($key2);
			}
		}
	}

/*each class had to redeclare this function getValidator, to define which validation must be done.  */
	/*protected function getValidator($inputs){
	}*/

/*	protected function id(){
		return $this->id; 
	}*/

/*	protected function errors(){
		return $this->errors; 
	}*/

}
