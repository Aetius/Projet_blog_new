<?php  

namespace App\utilities; 

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\controller\sessionController; 
use App\utilities\Purifier;

class CsrfMiddleware{

	private $formKey;
	private $sessionKey; 
	private $limit=50; 
	private $session=[]; 




	public function __construct($formKey='_csrf', $sessionKey='csrf'){
		sessionController::getSession(); //le & permet de faire appel à une référence. au lieu qu'une copie soit envoyée. 
		
		$this->formKey=$formKey; 
		$this->sessionKey=$sessionKey; 
		$this->getSession(); 

	}

	public function process($request, $delegate){
		if (in_array($request->getMethod(), ['POST', 'DELETE'])){  
			$params = $request->getParsedBody() ?:[];  
			
			if (!array_key_exists($this->formKey, $params)){ 
				$this->reject(); 
			} else { 
				$params[$this->formKey] = Purifier::htmlPurifier($params[$this->formKey]);  
				$csrfList = $this->session ??[]; 
				
				if (in_array($params[$this->formKey], $csrfList)){ 
					$this->useToken($params[$this->formKey]); 
					return $delegate->process($request); 
				}else{
					$this->reject(); 
				}
			}
		}else{
			return $delegate->process($request); 
		}
	}

	public function generateToken(){
		$token = bin2hex(random_bytes(16)); 
		

		$csrfList = $this->session ; 
		
		$csrfList[]=$token;   
		unset($this->session);
		$this->session=$csrfList;  
		$this->limitTokens(); 
		return $token; 
	}


	private function reject(){
		throw new \Exception(); 
	}

	private function useToken($token){
		$tokens = array_filter($this->session, function ($t) use ($token){
			return $token !== $t; 
		});
		$this->session[$this->sessionKey]=$tokens; 
	}

	private function limitTokens(){
		

		$tokens = $this->session ?? []; 
		while (count($tokens) > $this->limit) {
			array_shift($tokens); 
		
		}


		$_SESSION[$this->sessionKey] = $tokens; 
		
	}

/*	private function validSession(){
		if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        var_dump($_SESSION); 

        return $session = $_SESSION; 
	}*/

	public function getFormKey(): string
	    {
	        return $this->formKey;
	    }

	    private function getSession(){
	    	if (array_key_exists('csrf', $_SESSION)){
	    		$this->session = $_SESSION['csrf']; 
	    		//unset($_SESSION['csrf']); 

	    		return $this->session; 
	    	}
	    }



}