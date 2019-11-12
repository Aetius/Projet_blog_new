<?php

namespace App\controller;

use App\controller\Controller;
use App\model\EmailModel;
use Psr\Http\Message\ServerRequestInterface;




class EmailController extends Controller{
	private $modelEmail; 


	public function __construct(ServerRequestInterface $request){
        $this->modelEmail = new EmailModel();
        parent::__construct($request);
    }
	
	/**
	 *Send mail
	 */
	public function contact(){ 
		$inputs =$this->modelEmail->hydrate($this->request->getParsedBody());
		$errors = $this->modelEmail->prepareContact($inputs);
		
		if (!empty($errors)) {
			$_SESSION['success']['2']= "Echec lors de l'envoi du mail."; 
			$results['errors']=$errors; 
			$results['contact']=$inputs; 
			return $this->show('homePage', $results); 
		}
		$_SESSION['success']['1'] = "La demande de contact a bien été envoyée!";
		return header("location:/#contact"); 

		
		
	}

}









