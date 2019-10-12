<?php
namespace App\controller; 
use App\config\twigConfig;

class TwigController extends twigConfig{
	 

	public function __construct (){
		$this->config();
	}
	
	public function show($page, $results=[], $options=[]){ //var_dump($_SESSION); die(); 
		$display=[]; //var_dump($_SESSION); die(); 
		if (isset($_SESSION['success'])){
			$display['success']= $_SESSION['success'];
			unset($_SESSION['success']);
		}; 
		if (isset($_SESSION['errors'])){
			$results['errors']= $_SESSION['errors'];
			unset($_SESSION['errors']);
		}; 
		
		
			echo $this->twig->render("$page.twig", ['results'=> $results, 'options'=>$options, 'display'=>$display]);
		
	}
}