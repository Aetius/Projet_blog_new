<?php

    require '../vendor/autoload.php';


    use \App\Utilities\Router;
    use function Http\Response\send;
    use GuzzleHttp\Psr7\ServerRequest;
    use GuzzleHttp\Psr7\Response;
    use \App\utilities\Dispatcher;
    use \App\utilities\Auth;
    use \App\utilities\TrailingSlash;
    use \App\utilities\CsrfMiddleware;

//error_reporting(0); //Hide all php errors. Must be active in production. 


    $request = ServerRequest ::fromGlobals();

    $response = new Response();

    $dispatcher = new Dispatcher();
    $dispatcher
        -> pipe( new TrailingSlash() )
        -> pipe( new CsrfMiddleware() )
        -> pipe( new Auth() )
        -> pipe (new Router());

    send($dispatcher -> process( $request )) ;





