<?php

namespace App\Utilities;

/*use App\controller\UserController; */
use App\Model\UserModel;

class Auth{

	private $url; 

	/**
	 *Verify if user has access, and grant access or send a redirection
	 *@param string $request
	 *@param string $delegate
	 *@return $response
	*/
	public function process($request, $delegate){ 
		$response = $delegate->process($request); 
		$target = $request->getRequestTarget();  
		$partsTarget = explode('/', $target); 

		if (($partsTarget[1]==="admin")&& isset($partsTarget[2])) { 
			 $result = $this->userAccess(); 
			 if ($result === false) {
			 	$this->url = '/admin';
			 }
		
			if (($partsTarget[2]) === "users"){
				$result = $this->adminAccess(); 
				if ($result === false){
					$this->url = '/admin/dashboard';
				}
			}
			 if ($result === false){
			 	return $response
					->withHeader('Location', $this->url)
					->withStatus(302); 
			 }
		};
		return $response;  
	
	}

	/**
	 *verification of admin access
	 *@return bool
	 */
	private function adminAccess(){
		if (!array_key_exists('user', $_SESSION)){
			return false;
		}
		if ($_SESSION['user']['is_admin']!=="1"){
			return false; 
		}		
		return true; 
	}


    /**
     * Verification access
     * @return bool
     */
    public function userAccess(){
		$modelUser = new UserModel(); 
		if (!array_key_exists('access', $_SESSION)) {
			return false; 
		}
		if ($_SESSION['access']['auth'] !=='valide'){
			return false; 
		}
		$login = $_SESSION['access']['login'];
		$_SESSION['user']=$modelUser->access($login); 
		return true; 
	}






	/*public function process($request, $delegate){ 
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



	/*
* verify user's access in the auth utilities.
**/
	/*public function editorAccess(){ 
		if (array_key_exists('access', $_SESSION)) {
			if ($_SESSION['access']['auth'] ==='valide'){
				$login = $_SESSION['access']['login'];
				($_SESSION['user']=$this->modelUser->one('login', $login)); 
				return true;
			}
		}
	}*/
	

		
}

























	
/*
	public function process($request, $delegate){
		$response = $delegate->process($request);  
		return $response->withHeader('X-Powered-By', 'Grafikart'); 
	}
*/

