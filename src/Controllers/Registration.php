<?php

namespace Matvey\Test\Controllers;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: Role::GUEST)]
class Registration implements RequestHandlerInterface
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        if (!empty($this->user->parseUserFromBodyHttp($request->getParsedBody(), User::REGISTRATION))) {
            if (!$this->user->existUser()) {
                $this->user->save();
                return new RedirectResponse('index.php?ctrl=Login');
            }
        }

        return new Response(new Stream(__DIR__ . '/../../templates/registration.php'), 200);
    }
}