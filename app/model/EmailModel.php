<?php

namespace App\Model;


use App\Config\Constants;
use App\Utilities\Validator;

class EmailModel extends AppModel{

	private $name; 
	private $lastName; 
	private $email; 
	private $message; 


	/**
	 *Prepare send password to user
	 *Send inputs to send function. 
	 *@param string $password
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
	 *@return array|void
	 */
	public function prepareContact($inputs){
        $this->validation($inputs);
        if (!empty($this->errors)){
            return $this->errors;
        }
        $to = Constants::MAIL_TO;
        $subject= 'Message envoyé par ' . $this->lastName .' '. $this->name .'__'.'Email : '.$this->email ;
        $message_content = $this->message;
        $this->send($to, $subject, $message_content);

	}
	

	/**
	 *Setters
	 */

    /**
     * @param $value
     * @return string
     */
	public function setName($value){
		return $this->name = $value; 
	}

    /**
     * @param $value
     * @return string
     */
    public function setLastName($value){
		return $this->lastName = $value; 
	}

    /**
     * @param $value
     * @return string
     */
	public function setEmail($value){
		return $this->email = $value; 
	}

    /**
     * @param $value
     * @return string
     */
	public function setMessage($value){
		return $this->message = $value; 
	}


	/**
	 *Formate the email, and send it
	 *@param string $to
	 *@param string $subject
	 *@param string $message_content
	 *@return void
	 */
	protected function send($to, $subject, $message_content){
		$content = '<html lang="fr"><body>';
		$content .= '<p>'.nl2br($message_content).'</p>';
		$content .='</body></html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'FROM:' . Constants::MAIL_FROM;
		
		mail ($to, $subject, $content, $headers); 
	}

    /**
     *Validator
     * @param $inputs
     * @return Validator
     */
	protected function getValidator($inputs){
		$fieldsExist= ['name', 'lastName', 'email', 'message'];
		return(new Validator($inputs))
			->length('message', 10)
			->name('name')
			->name('lastName')
			->email('email')
			->emptyParam('envoi')
			->notEmpty($fieldsExist); 
	}
}