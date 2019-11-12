<?php

require '../vendor/autoload.php';
require '../app/config/routes.php';

use function Http\Response\send;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use GuzzleHttp\Psr7\ServerRequest; 
use GuzzleHttp\Psr7\Response; 
use \App\utilities\Dispatcher;
use \App\utilities\Auth;
use \App\utilities\TrailingSlash;  
use \App\utilities\CsrfMiddleware; 

//error_reporting(0); //Hide all php errors. Must be active in production. 

$router = new AltoRouter();


$router->addRoutes($routes);


$match = $router->match();



/*
*Middleware avec Psr7
**/

$request = ServerRequest::fromGlobals(); //permet de créer une requête à partir des variables globales //instance d'objet qui pourrait me permettre de ne jamais faire appel à $_POST.

$response = new Response(); 

$dispatcher = new Dispatcher();  
$dispatcher
	->pipe(new TrailingSlash())
	->pipe(new CsrfMiddleware())
	->pipe(new Auth());

send($dispatcher->process($request));


$page=new \App\controller\TwigController();



if(is_array($match)){ 
	if (preg_match('/#/', $match['target'])){ 
		$params = explode('#', $match['target']);
		$controller = '\App\controller\\'.$params[0]."Controller"; 
		$controller=new $controller($request);
		if(count ($match['params'])=="0"){
			array_push($match['params'], null);
		};
		return call_user_func_array([$controller, $params[1]], $match['params']);
	};
};
$page->show("error404");







