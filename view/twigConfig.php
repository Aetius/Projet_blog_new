<?php
namespace View;



class twigConfig{
	protected $twig;

	protected function config() {
		$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/templates');
		$this->twig = new \Twig\Environment($loader, [
	    	'cache' => false, //__DIR__.'/tmp',
	    	'debug' =>true,
	    	/*ne pas oublier de réactiver le cache lors de la mise en production.*/
		]);
		$this->twig->addExtension(new \Twig\Extension\DebugExtension());
		
			
	}
}