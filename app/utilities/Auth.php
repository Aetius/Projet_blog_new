<?php

namespace App\utilities; 

use App\controller\UserController; 

class Auth{

	public function process($request, $delegate){ 
		$response = $delegate->process($request); 
		$target = $request->getRequestTarget();  
		$partsTarget = explode('/', $target); 

		if (($partsTarget[1]==="admin")&& isset($partsTarget[2])) { 
			 $result = $this->editorVerif($partsTarget[2]); 

			 if ($result !== true){
			 	return $response
					->withHeader('Location', $result)
					->withStatus(302); 
			 }else{ 
				return $response;
			};
		}else{
			return $response;  
		}
	}


	private function adminVerif($auth){
		if($auth->adminAccess() !== true){
			return $url = '/admin/comments';
		}else{
			return true; 
		}
	}


	private function editorVerif($partsTarget){
		$auth= new UserController(); 
		
		if ($auth->editorAccess() !== true){ 
			return $url = '/admin'; 
		}elseif ($partsTarget === "users"){
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

