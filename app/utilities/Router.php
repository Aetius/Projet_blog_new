<?php

    namespace App\Utilities;


    use AltoRouter;
    use App\utilities\Templating;
    use GuzzleHttp\Psr7\Response;

    class Router{


        /**
         * @param $request
         * @param $delegate
         * @return Response
         * @throws \Twig\Error\LoaderError
         * @throws \Twig\Error\RuntimeError
         * @throws \Twig\Error\SyntaxError
         */
        public function process($request, $delegate){
            require '..\App\Config\routes.php';

            $router = new AltoRouter();
            $router -> addRoutes( $routes );
            $match = $router -> match();
            $twig = new Templating();


            try {
                if (is_array( $match )) {

                    if (preg_match( '/#/', $match['target'] )) {
                        $params = explode( '#', $match['target'] );
                        $controller = '\App\controller\\' . $params[0] . "Controller";
                        $controller = new $controller( $request );

                        if (count( $match['params'] ) == "0") {
                            array_push( $match['params'], null );
                        };
                        $prepareResponse = call_user_func_array( [$controller, $params[1]], $match['params'] );
                        $response = $delegate->process($request);

                        if (key_exists('redirect', $prepareResponse)){
                            return $response
                                ->withStatus(302)
                                ->withHeader('location', $prepareResponse['redirect']);
                        }
                        if (key_exists('show', $prepareResponse)){
                        $body = new Response(200, [], $twig->show(
                            $prepareResponse['show']['pageId'],
                            $prepareResponse['show']['results'],
                            $prepareResponse['show']['options']
                        ));
                            return $response->withBody($body->getBody());
                        };
                    };
                }
                return new Response(
                    404,
                    [],
                    $twig->show("error404")
                );

            }catch (\App\utilities\ErrorException $e) {
                return new Response(
                    500,
                    [],
                    $twig -> show( "500" )
                );
            }


        }
    }