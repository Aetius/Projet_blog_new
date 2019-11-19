<?php

namespace App\Controller;

use App\Controller\Controller;
use App\Model\EmailModel;
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
			$this->show('homePage', $results);
		}
		$_SESSION['success']['1'] = "La demande de contact a bien été envoyée!";
		$this->redirectTo("/#contact");
	}

}









