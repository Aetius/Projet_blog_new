<?php

namespace App\Utilities;

use App\Config\Constants;

class TrailingSlash {

	public function process($request, $delegate){

		$url = (string)$request->getUri();
		$response =$delegate->process($request);
		if (!empty($url) && ($url != Constants::getMainUrl()) && $url[-1] === '/'){
			return $response
				->withHeader('Location', substr($url,0, -1 ))
				->withStatus(301);
		}
		return $response;
	}
}