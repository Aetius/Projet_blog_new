<?php  

namespace App\Utilities;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Utilities\Session;
use App\Utilities\Purifier;

class CsrfMiddleware{

	private $formKey;
	private $sessionKey; 
	private $limit=50; 
	private $session=[]; 


	public function __construct($formKey='_csrf', $sessionKey='csrf'){
		Session::getSession();
		
		$this->formKey=$formKey; 
		$this->sessionKey=$sessionKey; 
		$this->getSession(); 
	}


    /**
     * @param $request
     * @param $delegate
     * @return mixed
     * @throws \Exception
     */
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


    /**
     * @return string
     */
    public function generateToken(){
		$token = bin2hex(random_bytes(16)); 
		$csrfList = $this->session ; 
		$csrfList[]=$token;   
		unset($this->session);
		$this->session=$csrfList;  
		$this->limitTokens(); 
		return $token; 
	}


    /**
     * @throws \Exception
     */
    private function reject(){
		throw new \Exception(); 
	}


    /**
     * @param string $token
     */
    private function useToken($token){
		$tokens = array_filter($this->session, function ($t) use ($token){
			return $token !== $t; 
		});
		$this->session[$this->sessionKey]=$tokens; 
	}


    /**
     *limite the number of tokens and delete the surplus
     */
    private function limitTokens(){
		$tokens = $this->session ?? []; 
		while (count($tokens) > $this->limit) {
			array_shift($tokens); 
		
		}
		$_SESSION[$this->sessionKey] = $tokens; 
		
	}


    /**
     * @return string
     */
    public function getFormKey(){
	        return $this->formKey;
	    }


    /**
     * @return array|mixed
     */
    private function getSession(){
    	if (array_key_exists('csrf', $_SESSION)){
    		$this->session = $_SESSION['csrf']; 
    		return $this->session; 
    	}
    }

}