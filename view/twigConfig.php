<?php
namespace View;

class twigConfig{
	protected $twig;

	protected function config() {
		$loader = new \Twig\Loader\FilesystemLoader(array (__DIR__.'/templates', __DIR__.'/templates/article', __DIR__.'/templates/comment', __DIR__.'/templates/user'));
		$this->twig = new \Twig\Environment($loader, [
	    	'cache' => false, //__DIR__.'/tmp',
	    	'debug' =>true,
	    	/*ne pas oublier de réactiver le cache lors de la mise en production.*/
		]);
		$this->twig->addExtension(new \Twig\Extension\DebugExtension());
		if (isset($_SESSION)){
			$this->twig->addGlobal('session', $_SESSION);
		}
			
	}
}