<?php

    namespace App\Controller;

    use App\Utilities\AppFactory;
    use App\Utilities\Session;
    use App\Utilities\Templating;
    use GuzzleHttp\Psr7\Response;
    use Psr\Http\Message\ServerRequestInterface;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;
    use function Http\Response\send;


    class Controller
    {

        /**
         *@var AppFactory
         */
        protected $factory;

        /**
         * @var ServerRequestInterface
         */
        protected $request;

        /**
         * Controller constructor.
         * @param ServerRequestInterface $request
         */
        public function __construct(ServerRequestInterface $request)
        {
            Session ::getSession();
            $this -> factory = AppFactory ::getInstance();
            $this -> request = $request;
        }

        /**
         * @param $url
         * @param array $results
         * @param array $options
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        protected function show($url, $results = [], $options = [])
        {
            $twig = new Templating();
            send( new Response(
                200,
                [],
                $twig -> show( "/$url", $results, $options )
            ));

        }

        /**
         * @param $url
         */
        protected function redirectTo($url)
        {
            send( new Response( 302, ['Location' => $url] ) );
        }

    }