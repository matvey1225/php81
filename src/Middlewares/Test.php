<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response\HtmlResponse;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Test implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $templates = TwigWorker::twig('testImage.php',[] );
        return new HtmlResponse($templates);
    }
}