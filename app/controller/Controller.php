<?php

    namespace App\Controller;

    use App\Utilities\AppFactory;
    use App\Utilities\Session;
    use Psr\Http\Message\ServerRequestInterface;


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

        public function __construct(ServerRequestInterface $request)
        {
            Session ::getSession();
            $this -> factory = AppFactory ::getInstance();
            $this -> request = $request;
        }


        /**
         * @param string $pageId
         * @param array $results
         * @param array $options
         * @return array
         */
        protected function show($pageId, $results = [], $options = [])
        {
            $preparePage['show'] = array(
                'pageId' => $pageId,
                'results' => $results,
                'options' =>$options
            );
            return $preparePage;
        }


        /**
         * @param string $url
         * @return array
         */
        protected function redirectTo($url)
        {
            $preparePage['redirect'] = $url;
            return $preparePage;
        }

    }