<?php

namespace Matvey\Test\Controllers;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: 'guest')]
class Login implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = new User();

        if (!empty($user->parseUserFromBodyHttp($request->getParsedBody(), 'login'))) {

            if ($user = $user->identification()) {
                setcookie('token', $user->getToken(), time() + 3600);
                return new Response\RedirectResponse("http://homework.local/test/index.php?ctrl=Home");

            }
        }

        return new Response(new Stream(__DIR__ . '/../../templates/login.php'), 200);

    }
}