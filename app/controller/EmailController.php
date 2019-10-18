<?php

namespace App\controller;

use App\controller\Controller;
use App\model\EmailModel; 
use View\twigView;



class EmailController extends controller{
	private $modelEmail; 


	public function __construct(){
		parent::__construct(); 
		$this->modelEmail = new EmailModel(); 
	}
	

	public function contact(){ 
		$inputs =$this->modelEmail->hydrate($_POST); 
		$errors = $this->modelEmail->prepareContact($inputs);
		
		if ( !isset($errors)){
			$_SESSION['success']['1'] = "La demande de contact a bien été envoyée!";
			header("location:/#contact"); 
		}else{
			$_SESSION['success']['2']= "Echec lors de l'envoi du mail."; 
			$results['errors']=$this->modelEmail->errors(); 
			$results['contact']=$inputs; 
			$this->show('homePage', $results); 
		}
	}

}









