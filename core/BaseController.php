<?php

namespace Core;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/*
 * A BaseController class, currently only equipped with a twig render function
 */
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