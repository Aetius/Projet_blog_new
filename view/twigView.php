<?php
namespace View; 

class twigView extends twigConfig{
	 

	public function __construct (){
		$this->config();
	}
	
	public function show($page, $var=[]){
		if (!empty($var)){
		/*	$test=$this->twig->load("$page.twig");
			echo $test->renderBlock("contact", ['var'=> $var]);*/
			echo $this->twig->render("$page.twig", ['var'=> $var]);
			
		}else{
		echo $this->twig->render("$page.twig");
		}
	}
}