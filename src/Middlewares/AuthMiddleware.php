<?php

namespace Matvey\Test\Middlewares;

use Matvey\Test\Models\User\User;
use Matvey\Test\Repositoryes\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class AuthMiddleware implements MiddlewareInterface
{

    protected Repository $repositoryUser;
    protected User  $user;

    public function __construct(Repository $repositoryUser, User $user)
    {
        $this->repositoryUser = $repositoryUser->setModel($user);
        $this->user=$user;
    }


    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $cookie = $request->getCookieParams();
        $user = null;

        if ((isset($cookie['token'])) && (!empty($cookie['token']))) {
            $this->user =$this->repositoryUser->getByParams('token', $cookie['token']);

        }

        if (!empty($this->user->getId())) {

            $request = $request
                ->withAttribute('role', $this->user->getRole())
                ->withAttribute('userName', $this->user->getName());
        } else {
            $request = $request->withAttribute('role', 'guest');
        }

        return $handler->handle($request);
    }
}