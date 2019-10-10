<?php
namespace App\config;

use App\utilities\CsrfMiddleware;

class CsrfExtension extends \Twig_Extension
{

    /**
     * @var CsrfMiddleware
     */
    private $csrfMiddleware;

    public function __construct()
    {
        $this->csrfMiddleware = new csrfMiddleware();
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']])
        ];
    }

    public function csrfInput()
    {
        return '<input type="hidden" ' .
            'name="' . $this->csrfMiddleware->getFormKey() . '" ' .
            'value="' . $this->csrfMiddleware->generateToken() . '"/>';
    }

    
}
