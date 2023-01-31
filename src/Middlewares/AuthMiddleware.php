<?php

namespace Matvey\Test\Middlewares;

use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class AuthMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $cookie = $request->getCookieParams();
        $user = null;

        if ((isset($cookie['token'])) && (!empty($cookie['token']))) {
            $user = User::getUserByParams('token', $cookie['token']);
        }

        if (!empty($user)) {
            $request = $request
                ->withAttribute('role', $user->getRole())
                ->withAttribute('userName', $user->getName());
        } else {
            $request = $request->withAttribute('role', 'guest');
        }

        return $handler->handle($request);
    }
}