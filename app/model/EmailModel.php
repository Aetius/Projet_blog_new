<?php

namespace App\model; 

use App\model\AppModel; 
use App\utilities\Validator;

class EmailModel extends AppModel{

	private $name; 
	private $lastName; 
	private $email; 
	private $message; 


	/**
	 *Prepare send password to user
	 *Send inputs to send function. 
	 *@param str $password
	 *@param array $inputs
	 **/
	public function preparePassword($password, $inputs){
		$to = $inputs['email'];
		$subject= "Récupération du mot de passe - site L-M";
		$message_content = "Bonjour ".$inputs['login']."; <p>Vous trouverez votre mot de passe provisoire. Merci de vous connecter et de le modifier dans votre espace personnel</p> <p>Votre mot de passe est $password.</p> <p>N'hésitez pas à revenir vers votre administrateur de site pour tout problème.</p> <p>Cordialement</p>"; 
		$this->send($to, $subject, $message_content);
		
	}

	/**
	 *Valide inputs. If validation is ok, send inputs to send function. 
	 *@param array $inputs
	 *@return array
	 */
	public function prepareContact($inputs){ 
		if (empty($this->validation($inputs))){
			$to = 'thomas.brumain@hotmail.fr';
			$subject= 'Message envoyé par ' . $this->lastName .' '. $this->name .'__'.'Email : '.$this->email ;
			$message_content = $this->message;
			$this->send($to, $subject, $message_content);
		};
		return $this->errors; 
		
	}
	

	/**
	 *Setters
	 */
	public function setName($value){
		return $this->name = $value; 
	}

	public function setLastName($value){
		return $this->lastName = $value; 
	}

	public function setEmail($value){
		return $this->email = $value; 
	}

	public function setMessage($value){
		return $this->message = $value; 
	}


	/**
	 *Formate the email, and send it
	 *@param str $to
	 *@param str $$subject
	 *@param str $message_content
	 *@return void
	 */
	protected function send($to, $subject, $message_content){
		$content = '<html><body>';
		$content .= '<p>'.nl2br($message_content).'</p>';
		$content .='</body></html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'FROM:' . 'dontomberry@outlook.com';
		
		mail ($to, $subject, $content, $headers); 
	}

	/**
	 *Validator
	 *@return obj
	 */
	protected function getValidator($inputs){ 
		$fieldsExist= ['name', 'lastName', 'email', 'message']; 
		return(new Validator($inputs))
			->length('message', 10)
			->name('name', 1)
			->name('lastName', 1) 
			->email('email')
			->empty('envoi')
			->notEmpty($fieldsExist); 
	}
}