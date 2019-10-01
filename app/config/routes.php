<?php

namespace App\config; 


$routes=(array(
    array('GET', '/connexion/create', 'article#showCreate'),
    array('POST','/connexion/create', 'article#create'),
    array('GET', '/connexion/comment/[i:id]', 'postConnexion#dashboard'),
    array('POST', '/connexion/comment/[i:id]', 'comment#manager'),
    array('GET','/connexion/update/[i:id]', 'article#showUpdate'),
    array('POST','/connexion/update/[i:id]', 'article#update'),
    /*array('GET', '/articles/delete/[i:id]', 'article#delete'),*/
   /*dans articleController. méthode show*/
   
    /*array('GET', '/articles/[i:id]', 'article#showOneArticle'),*/

   
  
   
   
   
    array('POST', '/connexion/dashboard[/page]?[i:id]?', 'postConnexion#routes'),

    array('GET', '/connexion/dashboard/article[/i:id]?', 'user#showdashboard'),
    //array('POST', '/connexion/dashboard[/page]?[i:id]?', 'article#dashboard'),

    //array('GET', '/connexion/dashboard[/page]?[i:id]?', 'user#showdashboard'),
    //array('POST', '/connexion/dashboard[/page]?[i:id]?', 'article#dashboard'),

    array('GET', '/connexion/settings', 'user#showSettings'),
    array('POST', '/connexion/settings', 'user#update'),
    array('GET', '/connexion/logout', 'user#logout'),
    //array('GET', '/comment', 'comment#show'),
    array('GET', '/articles/comment', 'comment#show'), 
    array('POST', '/articles/comment', 'comment#show'),






 array('GET', '/connexion/dashboard', 'article#showdashboard'),
    array('GET', '/connexion', 'user#showConnexion'),
    array('POST', '/connexion', 'user#connexion'),
    array('GET', '/inscription', 'user#showInscription'),
    array('POST', '/inscription', "user#inscription"),

    array('GET', '/articles/[i:id]', 'article#showOne'),
    array('POST', '/articles/[i:id]', 'comment#create'),
    array('GET', '/articles', 'article#showAll'),
    array('POST', '/', 'mail#sendMail'),
    array('GET', '/', 'home')
  //array('DELETE','/users/[i:id]', 'users#delete', 'delete_user')
));
