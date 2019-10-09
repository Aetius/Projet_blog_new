<?php

namespace App\utilities; 

use App\controller\UserController; 

class Auth{

	public function process($request, $delegate){
		$response = $delegate->process($request); 
		$target = $request->getRequestTarget();  
		$partsTarget = explode('/', $target); 
		$url = '/connexion'; 
		 
		//var_dump(isset($partsTarget[2])); die(); 
		if (($partsTarget[1]==="connexion")&& isset($partsTarget[2])) { 
			$auth= new UserController(); 
			if (!$auth->admin()){ 
				return $response
					->withHeader('Location', $url)
					->withStatus(302); 

			}else{ 
			 
				return $response;
			};

		}else{
			return $response;  

		}


	}
		
}

























	
/*
	public function process($request, $delegate){
		$response = $delegate->process($request);  
		return $response->withHeader('X-Powered-By', 'Grafikart'); 
	}
*/

