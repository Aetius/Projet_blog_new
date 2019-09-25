<?php

namespace App\controller; 

class Controller {
	
	protected function show($id, $result=[], $options=[]){
		$twig = new TwigController();
		$twig->show("/$id", $result, $options);

	}


//=> à développer pour rendre les fonctions show plus dynamiques. 
	/*protected function twig($id, $result=[], $options=[]){
		$twig = new TwigController();
		$twig->show("/$id", $result, $options);

	}


	public function show(){
		$parts=explode('::', __CLASS__); 
		$page=$parts[1]; 
		$this->twig('page'."Page"); 
	}*/
}