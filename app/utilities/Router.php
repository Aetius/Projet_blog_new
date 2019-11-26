<?php

    namespace App\Utilities;


    use AltoRouter;
    use GuzzleHttp\Psr7\Response;
    use Psr\Http\Message\RequestInterface;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    class Router
    {

        private $routes;

        public function __construct()
        {
            $this -> routes = (array(
                array('POST', '/admin/settings/email', 'user#emailUpdate'),
                array('POST', '/admin/settings/password', 'user#passwordUpdate'),
                array('POST', '/admin/settings/desactivate', 'user#desactivate'),
                array('POST', '/admin/users', 'user#dashboardAdmin'),
                array('GET', '/admin/users', 'user#showDashboardAdmin'),
                array('POST', '/admin/articles/[i:id]/delete', 'article#delete'),
                array('POST', '/admin/articles/[i:id]/comments/delete', 'comment#delete'),
                array('POST', '/admin/articles/[i:id]/comments', 'comment#update'),
                array('POST', '/admin/articles/[i:id]', 'article#updateArticle'),
                array('GET', '/admin/articles/[i:id]', 'article#showOneAdmin'),
                array('GET', '/admin/articles[/page/]?[i:id]?', 'article#showAllAdmin'),
                array('POST', '/admin/articles[/page/]?[i:id]?', 'article#updatePublication'),
                array('POST', '/admin/comments/comments/delete', 'comment#delete'),
                array('GET', '/admin/comments', 'comment#showDashboard'),
                array('POST', '/admin/comments', 'comment#update'),
                array('GET', '/admin/articles/create', 'article#showCreate'),
                array('POST', '/admin/articles/create', 'article#create'),
                array('GET', '/admin/logout', 'user#logout'),
                array('GET', '/admin/settings', 'user#showSettings'),
                array('GET', '/admin/dashboard', 'article#showDashboard'),
                array('GET', '/admin', 'user#showConnexion'),
                array('POST', '/admin', 'user#connexion'),
                array('GET', '/admin/settings/inscription', 'user#showInscription'),
                array('POST', '/admin/settings/inscription', "user#inscription"),
                array('GET', '/articles/[i:id]', 'article#showOne'),
                array('POST', '/articles/[i:id]/comments', 'comment#create'),
                array('GET', '/articles[/page/]?[i:id]?', 'article#showAll'),
                array('GET', '/password', 'user#showLost'),
                array('POST', '/password', 'user#lostPassword'),
                array('POST', '/contact', 'email#contact'),
                array('GET', '/', 'article#showHome')
            ));
        }


        /**
         * @param $request
         * @param $delegate
         * @return Response
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         * @throws \Exception
         */
        public function process(RequestInterface $request, Dispatcher $delegate)
        {

            $router = new AltoRouter();
            $router->addRoutes($this -> routes);
            $match = $router->match();
            $twig = new Templating();


            try {
                if (is_array($match)) {

                    if (preg_match('/#/', $match['target'])) {
                        $params = explode('#', $match['target']);
                        $controller = '\App\controller\\' . $params[0] . "Controller";
                        $controller = new $controller($request);

                        if (count($match['params']) == "0") {
                            array_push($match['params'], null);
                        };

                        $prepareResponse = call_user_func_array([$controller, $params[1]], $match['params']);
                        $response = $delegate -> process($request);

                        if (key_exists('redirect', $prepareResponse)) {
                            return $response
                                -> withStatus(302)
                                -> withHeader('location', $prepareResponse['redirect']);
                        }
                        if (key_exists('show', $prepareResponse)) {
                            $body = new Response(200, [], $twig->show(
                                $prepareResponse['show']['pageId'],
                                $prepareResponse['show']['results'],
                                $prepareResponse['show']['options']
                            ));
                            //return $response -> withBody($body -> getBody());
                            return $body;
                        };
                    };
                }
                return new Response(
                    404,
                    [],
                    $twig -> show("error404")
                );

            } catch (ErrorException $e) {
                return new Response(
                    500,
                    [],
                    $twig -> show("500")
                );
            }


        }
    }