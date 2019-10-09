<?php

namespace App\controller; 

use App\utilities\AppFactory;

class TestController{
	
	
	public function test(){
		$app = AppFactory::getInstance(); 


var_dump(get_class($this)); var_dump($app->getModel('user')); die(); 


	}

}