<?php

namespace Matvey\Test\Controllers;

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\User\User;
use Matvey\Test\Repositoryes\RepositoryUser;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: Role::GUEST)]
class Login implements RequestHandlerInterface
{
    protected User $user;
    protected RepositoryUser $repositoryUser;

    public function __construct(User $user, RepositoryUser $repositoryUser)
    {
        $this->repositoryUser = $repositoryUser;
        $this->user = $user;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
//method name ... //
        if (User::correctFilledBody($body, User::LOGIN)) {
            $userDb = $this->repositoryUser->getUserByParams(User::LOGIN, $body[User::LOGIN]);

            if ($userDb !== null) {
                $userPassword = $request->getParsedBody();

                if ($userDb->getPassword() === md5(trim($userPassword[User::PASSWORD]))) {
                    setcookie('token', $userDb->getToken(), time() + 3600);
                    return new RedirectResponse("index.php?ctrl=Home");
                }
            }
        }

        return new Response(new Stream(__DIR__ . '/../../templates/login.php'), 200);

    }
}