<?php

namespace App\Utilities;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Dispatcher {

    private $middlewares = [];
    private $index = 0;
    private $response;


    /**
     * @param $middleware
     * @return $this
     */
    public function pipe($middleware){
       $this->middlewares[] = $middleware;
       $this->response = new Response(); 
       return $this; 
    }


    /**
     * @param $request
     * @return mixed
     */
    public function process($request){
        $middleware = $this->getMiddleware();
        $this->index++;
        if (($middleware)=== null){
            return $this->response;
        }
        if (is_callable($middleware)) {
            return $middleware($request, $this->response, [$this, 'process']);
        } elseif ($middleware) {
            return $middleware->process($request, $this);
        }
    }

    /**
     * @return mixed|null
     */
    private function getMiddleware(){
        if (isset($this->middlewares[$this->index])) {
            return $this->middlewares[$this->index];
        }
        return null;
    }

}