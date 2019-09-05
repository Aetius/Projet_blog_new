<?php

require '../vendor/autoload.php';


$router = new AltoRouter();
/*var_dump(realpath('../vendor/tinymce/tinymce/tinymce.min.js'));
die();*/
//$router->map('GET', '/articles/', 'Articles#show', 'test');

$router->addRoutes(array(
  array('GET', '/articles/create', 'article#showCreate'),
  array('POST','/articles/create', 'article#create'),
  array('GET','/articles/update/[i:id]', 'article#showUpdate'),
  array('POST','/articles/update/[i:id]', 'article#update'),
  array('GET', '/articles/delete/[i:id]', 'article#delete'),
  array('GET', '/articles/[i:id]', 'article#showOneArticle'),
  array('GET', '/articles', 'article#showArticles'),
  array('GET', '/inscription', 'user#showInscription'),
  array('POST', '/inscription', "user#inscription"),
  array('GET', '/connexion', 'user#showConnexion'),
  array('POST', '/connexion', 'user#connexion'),
  array('GET', '/connexion/dashboard', 'user#showdashboard'),
  array('POST', '/connexion/dashboard', 'article#dashboard'),
  //array('DELETE', '/connexion/dashboard/[delete:action][i:id]', 'article#delete'),
  //array('DELETE', '/connexion/dashboard', 'article#delete'),
  array('GET', '/connexion/settings', 'user#showSettings'),
  array('POST', '/connexion/settings', 'user#update'),
  array('GET', '/connexion/logout', 'user#logout'),
  array('GET', '/comment', 'comment#show'),
  array('GET', '/comment/create', 'comment#show'), 
  array('POST', '/comment/create', 'comment#create'),
  array('POST', '/', 'mail#sendMail'),
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