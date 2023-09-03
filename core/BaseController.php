<?php

namespace Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BaseController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function renderTemplate($pathToTemplate, $context = [])
    {
        return Application::$twig->render($pathToTemplate, $context);
    }


}