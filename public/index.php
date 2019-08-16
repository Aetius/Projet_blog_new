<?php

require '../vendor/autoload.php';


$router = new AltoRouter();



//$router->map('GET', '/articles/', 'Articles#show', 'test');

$router->addRoutes(array(
  array('POST','/articles/create', 'article#create'),
  array('GET', '/articles/create', 'article#showCreate'),
  array('GET', '/articles/delete/[i:id]', 'article#delete'),
  array('GET', '/articles/[i:id]', 'article#showOneArticle'),
  array('GET', '/articles', 'article#showArticles'),
  array('GET', '/inscription', 'user#show'),
  array('POST', '/inscription', "user#inscription"),
  array('GET', '/connexion', 'user#show'),
  array('POST', '/connexion', 'user#connexion'),
  array('GET', '/connexion/espace', 'user#show'),
  array('GET', '/connexion/logout', 'user#logout'),
  array('GET', '/contact', 'user#show'),
  array('GET', '/comment', 'comment#show'),
  array('GET', '/comment/create', 'comment#show'), 
  array('POST', '/comment/create', 'comment#create'),
  array('POST', '/', 'mail#sendContact'),
  array('GET', '/', 'home')
  //array('DELETE','/users/[i:id]', 'users#delete', 'delete_user')
));

//////////ajouter un push array pour mettre la fin de l'url dans match params. 
///obj : récupérer create, ou inscription... pour pouvoir le passer en paramètres et créer une fonction show générique, qui prendra un id pour appeler le bon twig.

$match = $router->match();
$page=new \View\twigView();

if(is_array($match)){
	if (preg_match('/#/', $match['target'])){ 
		$params = explode('#', $match['target']);
		$controller = '\App\controller\\'.$params[0].'Controller'; 
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