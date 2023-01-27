<?php

namespace Matvey\Test\Models\TwigWorker;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class TwigWorker
{

    /**
     * @param string $templateName
     * @param array $twigData
     * @return string|null
     */
    public static function twig(string $templateName, array $twigData):string|null
    {
        try{
            $loader = new FilesystemLoader(__DIR__ . '/../../../templates');
            $twig = new Environment($loader);
            $template = $twig->load( $templateName);
            return $twig->render($template,$twigData);

        }catch (\Throwable $throwable){
            return null;
        }

}

}