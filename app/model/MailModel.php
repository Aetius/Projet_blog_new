<?php

namespace App\model; 

use App\model\AppModel; 


class MailModel extends AppModel{



	public function prepareMessage($to, $subject, $message_content){
		$content = '<html><body>';
		$content .= '<p>'.nl2br($message_content).'</p>';
		$content .='</body></html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= 'FROM:' . 'dontomberry@outlook.com';
		mail ($to, $subject, $content, $headers);
	}



	protected function getValidator($inputs){
		$fieldsExist= ['name', 'lastname', 'email', 'login', 'password', 'passwordConfirm']; 
		return(new Validator($inputs))
			->length('Content', 1)
			->length('Description', 1)
			->length('Title', 1)
			->name('Title'); 
			->notEmpty($fieldsExist); 
	}





}