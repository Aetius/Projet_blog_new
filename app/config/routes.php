<?php

namespace App\config; 


$routes=(array(
   
 
    
    //array('GET','/connexion/update/[i:id]', 'article#showUpdate'),
  //  array('POST','/connexion/update/[i:id]', 'article#updateArticle'),
    /*array('GET', '/articles/delete/[i:id]', 'article#delete'),*/
   /*dans articleController. méthode show*/
   
    /*array('GET', '/articles/[i:id]', 'article#showOneArticle'),*/

  
   
   // array('POST', '/connexion/dashboard[/page]?[i:id]?', 'postConnexion#routes'),

   
    //array('POST', '/connexion/dashboard[/page]?[i:id]?', 'article#dashboard'),

    //array('GET', '/connexion/dashboard[/page]?[i:id]?', 'user#showdashboard'),
    //array('POST', '/connexion/dashboard[/page]?[i:id]?', 'article#dashboard'),

    
  
    //array('GET', '/comment', 'comment#show'),
    //array('GET', '/articles/comment', 'comment#show'), 
   // array('POST', '/articles/comment', 'comment#show'),



 //array('DELETE', '/admin/articles/[i:id]', 'article#delete'), //pas de méthode delete accessible via un form. en tout cas, pas trouvé. 
    



 
//pour les update, c'est un put
array('POST', '/admin/users', 'user#dashboardAdmin'), 
    array('GET', '/admin/users', 'user#showDashboardAdmin'),

    array('POST', '/admin/articles/[i:id]/comments', 'comment#managerArticlePage'), 
    array('POST', '/admin/articles/[i:id]', 'article#managerArticle'), 
    array('GET', '/admin/articles/[i:id]', 'article#showOneAdmin'),
    array('GET', '/admin/articles[/page/]?[i:id]?', 'article#showAllAdmin'),
    array('POST', '/admin/articles', 'article#updatePublication'),

    array('GET', '/admin/comments', 'comment#showDashboard'),  
    array('POST', '/admin/comments', 'comment#managerCommentsPage'),
    array('GET', '/admin/articles/create', 'article#showCreate'),
    array('POST','/admin/articles/create', 'article#create'), 
    array('GET', '/admin/logout', 'user#logout'),
    array('GET', '/admin/settings', 'user#showSettings'),
    array('POST', '/admin/settings', 'user#settings'), 
    array('GET', '/admin/dashboard', 'article#showDashboard'),
    array('GET', '/admin', 'user#showConnexion'),
    array('POST', '/admin', 'user#connexion'),
    array('GET', '/admin/settings/inscription', 'user#showInscription'),
    array('POST', '/admin/settings/inscription', "user#inscription"),
    array('GET', '/articles/[i:id]', 'article#showOne'),
    array('POST', '/articles/[i:id]/comments', 'comment#create'),
    array('GET', '/articles[/page/]?[i:id]?', 'article#showAll'),  
    array('GET', '/test', 'test#test'), 
   // array('GET', '/articles', 'article#showAll'),
    array('POST', '/contact', 'mail#sendMail'),  
    array('GET', '/', 'home')
  //array('DELETE','/users/[i:id]', 'users#delete', 'delete_user')
));
