<?php

    namespace App\Utilities;

    use App\Config\TwigConfig;
    use Twig\Error\LoaderError;
    use Twig\Error\RuntimeError;
    use Twig\Error\SyntaxError;

    class Templating extends TwigConfig
    {


        public function __construct()
        {
            $this->config();
        }

        /**
         * @param $page
         * @param array $results
         * @param array $options
         * @return string
         * @throws LoaderError
         * @throws RuntimeError
         * @throws SyntaxError
         */
        public function show($page, $results = [], $options = [])
        {
            $display = [];
            if (isset($_SESSION['success'])) {
                $display['success'] = $_SESSION['success'];
                unset($_SESSION['success']);
            };
            if (isset($_SESSION['errors'])) {
                $results['errors'] = $_SESSION['errors'];
                unset($_SESSION['errors']);
            };


            return $this->twig->render(
                "$page.twig",
                [
                    'results' => $results,
                    'options' => $options,
                    'display' => $display
                ]
            );
        }

    }
