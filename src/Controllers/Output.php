<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Role\Role;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[RoleHandlerAttribute(role: Role::GENERAL)]
class Output implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $cookie = $request->getCookieParams();

        if (isset($cookie['token'])) {
            setcookie('token', '', time() - 3600);
        }

        session_destroy();

        return new Response\RedirectResponse('index.php?ctrl=Registration');
    }
}