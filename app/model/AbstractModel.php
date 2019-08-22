<?php
namespace Model;


abstract class AbstractModel{
	protected $_bdd;

	protected function __construct(){

		$newBDD = new BDDConnection();
		$this->_bdd = $newBDD->connection();
		return ($self->_bdd);
	}

	/*abstract public function create(){

	}

	abstract public function read(){

	}

	abstract public function delete(){

	}

	abstract public function update(){

	}*/


}