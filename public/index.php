<?php

require '../vendor/autoload.php';
require '../app/config/routes.php';

$router = new AltoRouter();
/*var_dump(realpath('../vendor/tinymce/tinymce/tinymce.min.js'));
die();*/
//$router->map('GET', '/articles/', 'Articles#show', 'test');


//////////ajouter un push array pour mettre la fin de l'url dans match params. 
///obj : récupérer create, ou inscription... pour pouvoir le passer en paramètres et créer une fonction show générique, qui prendra un id pour appeler le bon twig.

$router->addRoutes($routes);


$match = $router->match();
$page=new \App\controller\TwigController();

if(is_array($match)){
	if (preg_match('/#/', $match['target'])){ 
		$params = explode('#', $match['target']);
		$controller = '\App\controller\\'.$params[0]."Controller"; 
		$controller=new $controller;
		if(count ($match['params'])=="0"){
			array_push($match['params'], null);
		};
		return call_user_func_array([$controller, $params[1]], $match['params']);
	}else {
		$page->show("homePage"); 

	}
}else{
    $page->show("error404");
}



///////var_dump($_SERVER['REQUEST_URI']); die(); 




/*
if(is_array($match)){
    if (is_callable($match['target'])){
        call_user_func_array($match['target'],$match['params']); // Cette ligne executes les fonctions anonymes des test 1 & 2
    }else{
        $params = $match['params'];
       require  "../Views/{$match['target']}.php"; // Cette ligne execute le test 3.  A noter que la page php se situe dans le dossier "Views"
    }

}else{
    die('Erreur 404');
}
*/