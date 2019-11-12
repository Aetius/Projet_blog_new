<?php

namespace App\config; 


$routes=(array(
     array('POST', '/admin/settings/email', 'user#emailUpdate'),
    array('POST', '/admin/settings/password', 'user#passwordUpdate'),
    array('POST', '/admin/settings/desactivate', 'user#desactivate'),
    array('POST', '/admin/users', 'user#dashboardAdmin'), 
    array('GET', '/admin/users', 'user#showDashboardAdmin'),
    array('POST', '/admin/articles/[i:id]/delete', 'article#delete'), 
    array('POST', '/admin/articles/[i:id]/comments/delete', 'comment#delete'), 
    array('POST', '/admin/articles/[i:id]/comments', 'comment#update'), 
    array('POST', '/admin/articles/[i:id]', 'article#updateArticle'), 
    array('GET', '/admin/articles/[i:id]', 'article#showOneAdmin'),
    array('GET', '/admin/articles[/page/]?[i:id]?', 'article#showAllAdmin'),
    array('POST', '/admin/articles[/page/]?[i:id]?', 'article#updatePublication'),
    array('POST', '/admin/comments/delete', 'comment#delete'),
    array('GET', '/admin/comments', 'comment#showDashboard'),  
    array('POST', '/admin/comments', 'comment#update'),
    array('GET', '/admin/articles/create', 'article#showCreate'),
    array('POST','/admin/articles/create', 'article#create'), 
    array('GET', '/admin/logout', 'user#logout'),
    array('GET', '/admin/settings', 'user#showSettings'),
    //array('POST', '/admin/settings', 'user#settings'), 
    array('GET', '/admin/dashboard', 'article#showDashboard'),
    array('GET', '/admin', 'user#showConnexion'),
    array('POST', '/admin', 'user#connexion'),
    array('GET', '/admin/settings/inscription', 'user#showInscription'),
    array('POST', '/admin/settings/inscription', "user#inscription"),
    array('GET', '/articles/[i:id]', 'article#showOne'),
    array('POST', '/articles/[i:id]/comments', 'comment#create'),
    array('GET', '/articles[/page/]?[i:id]?', 'article#showAll'),  
    array('GET', '/password', 'user#showLost'), 
    array('POST', '/password', 'user#lostPassword'), 
    array('POST', '/contact', 'email#contact'),  
    array('GET', '/', 'article#showHome')
));
