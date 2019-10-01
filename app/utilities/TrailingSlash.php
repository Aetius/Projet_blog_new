<?php

namespace App\utilities; 

class TrailingSlash {

	public function process($request, $delegate){

		$url = (string)$request->getUri(); 
		$response =$delegate->process($request); 
		if (!empty($url) && ($url != 'http://projet5blog/') && $url[-1] === '/'){
			return $response
				->withHeader('Location', substr($url,0, -1 ))
				->withStatus(301);
		}
		return $response;
	}
}