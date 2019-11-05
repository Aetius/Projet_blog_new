<?php

namespace App\controller;

use App\controller\Controller;
use App\model\EmailModel; 
use View\twigView;



class EmailController extends Controller{
	private $modelEmail; 


	public function __construct(){
		parent::__construct(); 
		$this->modelEmail = new EmailModel(); 
	}
	
	/**
	 *Send mail
	 **@return page
	 */
	public function contact(){ 
		$inputs =$this->modelEmail->hydrate($_POST); 
		$errors = $this->modelEmail->prepareContact($inputs);
		
		if ($errors != empty){
			$_SESSION['success']['2']= "Echec lors de l'envoi du mail."; 
			$results['errors']=$errors; 
			$results['contact']=$inputs; 
			return $this->show('homePage', $results); 
		}
		$_SESSION['success']['1'] = "La demande de contact a bien été envoyée!";
		return header("location:/#contact"); 

		
		
	}

}









