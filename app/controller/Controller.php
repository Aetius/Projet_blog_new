<?php

namespace App\controller; 

use App\utilities\AppFactory;
use Psr\Http\Message\ServerRequestInterface;


class Controller {

	protected $factory;
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    public function __construct(ServerRequestInterface $request){
		sessionController::getSession(); 
		$this->factory = AppFactory::getInstance();
        $this -> request = $request;
    }
	
	protected function show($id, $result=[], $options=[]){
		$twig = new TwigController();
		$twig->show("/$id", $result, $options);

	}

}