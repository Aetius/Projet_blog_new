<?php

namespace App\model;

use \PDO; 

class Model{
	protected $db;
	protected $table; 

	public function __construct (){
		$this->db=(BDDConnection::connection());
		if ($this->table === null){
			$parts= explode("\\", get_class($this));
			$this->table = strtolower(str_replace("Model", "", end($parts)))."s";
		}
	}

/*
*permet de renseigner le nom de la table.
*/
/*	abstract public function getTable():String{

	}*/



	public function create($fields){
		$sqlParams=[]; 
		$attributes=[];
		foreach ($fields as $key => $value) {
			$sqlParams[]="$key=?";
			$attributes[]= $value;
		}
		$sqlParams = implode(',', $sqlParams);
		return $this->prepareRequest("INSERT INTO {$this->table} SET $sqlParams", $attributes, true);
	}


	public function all(){
		return $this->prepareRequest("SELECT * FROM {$this->table} ORDER BY id DESC");
	}

	public function one($fieldName, $field){ 
		return $this->prepareRequest("SELECT * FROM {$this->table} WHERE $fieldName=:field", [":field"=>$field], true);
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
 		return $this->prepareRequest("UPDATE {$this->table} SET $sqlParams WHERE id=?", $attributes, true);
	}

	public function delete($id, $field='id'){
		return $this->prepareRequest("DELETE FROM {$this->table} WHERE $field=:id", [":id"=>$id], true );
	}


	protected function prepareRequest($statement, $attributes=null, $one=false){ 
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



}