<?php
namespace App\controller;


class SessionController{

	public function run($name){
		/*$_SESSION['login']=$getLogin;
		$_SESSION['time']=1000; */
		session_start();
		/*$newId=session_create_id('test');
		session_commit();
		ini_set('session.use_strict_mode', 1);
		session_id($newId);*/

		$_SESSION['user'] = [
			'username' => 'tom',
			'mot de passe' => 'coucou']; 
		
	


	}

	static function connexionSession(){
		if ((isset ($_SESSION['user']['username'])) && (isset($_SESSION['user']['mot de passe']))){
			extract($_SESSION['user']); 
			echo 'connexion OK';
		}else{
			header('Location:/connexion');
		}
	}

	static function logout(){
		session_start();
		$_SESSION=array();
		session_destroy();
	}

}