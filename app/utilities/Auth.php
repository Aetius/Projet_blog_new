<?php

namespace App\utilities; 

use App\controller\UserController; 

class Auth{

	private $url; 

	public function process($request, $delegate){ 
		$response = $delegate->process($request); 
		$target = $request->getRequestTarget();  
		$partsTarget = explode('/', $target); 

		if (($partsTarget[1]==="admin")&& isset($partsTarget[2])) { 
			 $result = $this->editorVerif($partsTarget[2]); 

			 if ($result !== true){
			 	return $response
					->withHeader('Location', $this->url)
					->withStatus(302); 
			 }else{ 
				return $response;
			};
		}else{
			return $response;  
		}
	}


	private function adminVerif($auth){
		if (array_key_exists('user', $_SESSION)){
			if ($_SESSION['user']['is_admin']==="1"){
				return true; 
			}
		}
		$this->url = '/admin/dashboard';
		return false; 
	}

	


	private function editorVerif($partsTarget){ 
		$auth= new UserController(); 
		
		if ($auth->editorAccess() !== true){ 
			$this->url = '/admin';
			return false; 
		}
		if ($partsTarget === "users"){ 
			return $this->adminVerif($auth); 
		}else{
			return true;
		}
	}
	

		
}

























	
/*
	public function process($request, $delegate){
		$response = $delegate->process($request);  
		return $response->withHeader('X-Powered-By', 'Grafikart'); 
	}
*/

