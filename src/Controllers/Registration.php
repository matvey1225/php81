<?php

namespace Matvey\Test\Controllers;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Middlewares\Repositories\UserRepository;
use Matvey\Test\Models\Role;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role:  Role::GUEST)]
class Registration implements RequestHandlerInterface
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        if ($this->userRepository->newUser($request)) {
            return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Login');
        }

        return new Response(new Stream(__DIR__ . '/../../templates/registration.php'), 200);

    }
}