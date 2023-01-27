<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class Auth implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cookie = $request->getCookieParams();


        if ((isset($cookie['token'])) && (!empty($cookie['token']))) {
            $user = User::getUserByParams('token', $cookie['token']);
            $request = $request->withAttribute('role', $user->getRole()) ->withAttribute('userName', $user->getName());
        }else{
            $request = $request->withAttribute('role', 'guest') ;
        }

        return $handler->handle($request);
    }
}