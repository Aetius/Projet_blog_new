<?php

    namespace App\Controller;

    use App\Utilities\AppFactory;
    use App\Utilities\Session;


    class Controller
    {

        /**
         *@var AppFactory
         */
        protected $factory;

        public function __construct()
        {
            Session ::getSession();
            $this -> factory = AppFactory ::getInstance();
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