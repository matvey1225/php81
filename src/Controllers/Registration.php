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
class Registration implements RequestHandlerInterface
{

    /**
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $user = new User();
        if (!empty($user->parseUserFromBodyHttp($request->getParsedBody(), 'registration'))) {

            if (!$user->existUser()) {
                $user->save();
                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Login');
            }
        }
        return new Response(new Stream(__DIR__ . '/../../templates/registration.php'), 200);

    }
}