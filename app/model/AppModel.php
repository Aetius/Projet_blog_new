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
	protected $saveInputs;


	public function __construct (){
		$this->db=(BDDConnection::connection());
		if ($this->table === null){
			$parts= explode("\\", get_class($this));
			$this->table = strtolower(str_replace("Model", "", end($parts)))."s";
		}
	}

	/**
	 *Hydrate function to the setters
	 *@param array $verifyInputs
	 *@return array
	 */
	public function hydrate($verifyInputs){
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
	 *Create statement
	 *@param array $field
	 *@return str
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

	/**
	 *All statement : search all entries in db
	 *@return str
	 */
	public function all(){
		return $this->executeRequest("SELECT * FROM {$this->table} ORDER BY id DESC");
	}

	/**
	 *One statement : search one entry in db
	 *@param string $fieldName
	 *@param string $field
	 *@return array
	 */
	public function one($fieldName, $field){
		return $this->executeRequest("SELECT * FROM {$this->table} WHERE $fieldName=:field", [":field"=>$field], true);
	}

	/**
	 *Search statement : search all entries in db with one criteria
	 *@param string$fieldName
	 *@param string $field
	 *@return array
	 */
	/*public function search($fieldName, $field){
		return $this->executeRequest("SELECT * FROM {$this->table} WHERE $fieldName=:fieldName ORDER BY id DESC", [":fieldName"=>$field]);
	}*/


    /**
     * @param $search
     * @return array
     */
    public function search2($search){
        $statement = "";
        for ($i = 0; $i<count($search); $i++){
	        $statement .= $search[$i]['field']. $search[$i]['operator']. ':'.$search[$i]['field'];
	        $attributes[':'.$search[$i]['field']]= $search[$i]['value'];

            if ($i != array_key_last($search)){
                $statement .= " AND ";
            };
        }

        return $this->executeRequest("SELECT * FROM {$this->table} WHERE $statement ORDER BY id DESC", $attributes);
	}



	/**
	 *Update statement
	 *@param array $fields
	 *@param int $id
	 *@return string
	 */
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

	/**
	 *Delete statement
	 *@param int $id
	 *@param str $field
	 *@return str
	 */
	public function delete($id, $field='id'){
		return $this->executeRequest("DELETE FROM {$this->table} WHERE $field=:id", [":id"=>$id], true );
	}


	/**
	 *Getters
	 */
	public function errors(){
		$result['errors'] = $this->errors;
		return $result;
	}

	public function saveInputs(){
		return $this->saveInputs;
	}


	/**
	 *Execute the statement to the db
	 *@param str $statement
	 *@param array $attributes
	 *@param int $one
	 *@return array $result
	 */
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

	/**
	 *Add errors with initial inputs to return an array that will show both
	 *@param array $fields
	 *@return array $result
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

	/**
	 *Validate if the record in db is true
	 *@param str $function
	 *@param array $fields
	 *@return bool
	*/
	protected function recordValid($function, $fields){
		$id=$this->id;

		if (($this->$function($fields, $id)!==true)){
			$this->errors[]="L'enregistrement a échoué";
			return false;
		}
		return true;
	}


	/**
	 *Inputs validation
	 *@param array $inputs
	 *@param str $validator
	 *@return void
	 */
	protected function validation($inputs, $validator="getValidator"){
		$validator = $this->$validator($inputs);
		if ($validator->isValid()!= true){
			$this->errors = array_merge($this->errors, $validator->getErrors());
		}
	}

	/**
	 *inputs validation
	 *@param array $inputs
	 *@return object
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
