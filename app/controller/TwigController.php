<?php
namespace App\controller; 
use App\config\twigConfig;

class TwigController extends twigConfig{
	 

	public function __construct (){
		$this->config();
	}
	
	public function show($page, $results=[], $options=[]){ 
		$display=[]; 
		if (isset($_SESSION['success'])){
			$display['success']= $_SESSION['success'];
			unset($_SESSION['success']);
		}
		/*var_dump($page); var_dump($results); die(); */
			echo $this->twig->render("$page.twig", ['results'=> $results, 'options'=>$options, 'display'=>$display]);
		
	}
}