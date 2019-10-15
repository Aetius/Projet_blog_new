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




	
/*

	
	public function sendMail(){
		$errors="";
		$this->labelValidation($errors);
		
		//header('location:/#contact');
		$page=new twigView();
		$page->show('homePage');
		/*unset($_SESSION['success']);
		unset ($_SESSION['errors']); 
		unset ($_SESSION['inputs']);*/
	}

	*/



/*	

	private function confirmationInscription(){

	}

	private function messagePassword(){

	}
*/

/*on peut faire une classe sender
il faut au min le message ou le mail. 
l'idéal : la validation dans une classe mailSender. dedans, on met les propriétés email/message/name (en option)
à l'int de formvalidation : 
$mailSender = new MailSender();
$mailSender->validate($_POST)  => on fait la validation dedans.
l'autre classe (mailSender) c'est un peu un modèle. 

dans validate : on crée un classe validator. et on peut reprendre ce qu'il y a dans la classe verification. 
séparé avec une classe purification. 
*/
	/*private function formValidation($errors){
		if (empty ($_POST['surname'])|| (Verification::name($_POST['surname']) !='valide')) {
			$errors = "$errors" ."Le prénom est incorrect. ";
		}if (empty ($_POST['name'])|| (Verification::name($_POST['name']) !='valide')) {
			$errors = "$errors" ."Le nom est incorrect. ";
		}if (empty ($_POST['email'])|| (Verification::mail($_POST['email']) !='valide')) {
			$errors = "$errors" ."L'email est incorrect. ";
		}if (empty ($_POST['message'])|| (strlen($_POST['message']))<10) {
			$errors = "$errors" ."Le message est incorrect. ";
		}if (isset($_POST['envoi'])){
			$errors="$errors"."Je suis un robot.";
		}
		return $errors;
	}*/

	/*private function labelValidation ($errors){


		foreach ( $_POST as $k => $v){
			var_dump($k);
			var_dump($v);
			if ((strlen ($v) == 0) || ((!$k =='email') && (!Verification::name($v) =='valide')) || (($k =='email') && (!Verification::mail($v) =='valide'))){
				$errors = "$errors" ."Le champ $k est incorrect. ";
			}
		}
		$errors = $this->formValidation($errors);
		
		if (!empty($errors)){
			$_SESSION['errors']=$errors;
			$_SESSION['inputs']=$_POST;
			$_SESSION['success']=2;
		}else{
			$_SESSION['success']=1;
			$this->messageContact();
		}
	}*/




		
	
}










