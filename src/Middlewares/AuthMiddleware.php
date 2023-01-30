<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response;
use Matvey\Test\Middlewares\Repositories\UserRepository;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class AuthMiddleware implements MiddlewareInterface
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->userRepository->detectedUser($request);
        return $handler->handle($request);
    }
}