<?php
namespace App\Config;

use App\utilities\CsrfMiddleware;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class CsrfExtension extends AbstractExtension {

    private $csrfMiddleware;

    public function __construct()    {
        $this->csrfMiddleware = new csrfMiddleware();
    }

    public function getFunctions(){
        return [
            new TwigFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => ['html']])
        ];
    }

    public function csrfInput(){
        return '<input type="hidden" ' .
            'name="' . $this->csrfMiddleware->getFormKey() . '" ' .
            'value="' . $this->csrfMiddleware->generateToken() . '"/>';
    }

    
}
