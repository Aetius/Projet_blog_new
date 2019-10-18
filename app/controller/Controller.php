<?php

namespace App\controller; 

use App\utilities\AppFactory;


class Controller {

	protected $factory;

	public function __construct(){
		sessionController::getSession(); 
		$this->factory = AppFactory::getInstance(); 
	}
	
	protected function show($id, $result=[], $options=[]){
		$twig = new TwigController();
		$twig->show("/$id", $result, $options);

	}

}