<?php
namespace App\Config;

use Twig\Environment;

class TwigConfig{

    /** @var  Environment */
	protected $twig;

	protected function config() {

		$path=((dirname($_SERVER['DOCUMENT_ROOT']))."/Views");

		$loader = new \Twig\Loader\FilesystemLoader(array ("$path/templates", "$path/article", "$path/comment", "$path/user"));
		$this->twig = new \Twig\Environment($loader, [
	    	'cache' => false, //__DIR__.'/tmp',
	    	'debug' =>true,
	    	/*ne pas oublier de réactiver le cache lors de la mise en production.*/
		]);
		$this->twig->addExtension(new \Twig\Extension\DebugExtension());
		$tokenAdd = new CsrfExtension(); 
		$this->twig->addExtension($tokenAdd); 
		if (isset($_SESSION)){
			$this->twig->addGlobal('session', $_SESSION);
		}
			
	}
}