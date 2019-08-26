<?php 

namespace App\controller;

abstract class abstractController{


protected function showAdmin($page){
		session_start();
		if (array_key_exists('access', $_SESSION)) {
			if ($_SESSION['access']['auth'] ==='valide'){
				$login = $_SESSION['access']['login'];
				$this->show($page, $this->getData($login));
			}
		}else{
			header('Location:/connexion');
		}
	}


	protected function show($id, $result=[]){
		$twig = new \View\twigView();
		$twig->show("/user/$id".'Page', $result);
	}




}