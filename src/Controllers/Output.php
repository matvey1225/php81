<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Output implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cookie = $request->getCookieParams();

        if (isset($cookie['token'])){
            setcookie('token', '', time()-3600);
        }

        session_destroy();

        return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Registration') ;
    }
}