<?php

namespace Matvey\Test\Middlewares\Repositories;

use http\Env\Request;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\RequestInterface;

class UserRepository
{

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function identification(RequestInterface $request): bool
    {
        if (!empty($this->user->parseUserFromBodyHttp($request->getParsedBody(), 'login'))) {
            if ($user = $this->user->identification()) {
                setcookie('token', $user->getToken(), time() + 3600);
                return true;
            }
        }
        return false;
    }


    public function newUser(RequestInterface $request): bool
    {
        if (!empty($this->user->parseUserFromBodyHttp($request->getParsedBody(), 'registration'))) {
            if (!$this->user->existUser()) {
                $this->user->save();
                return true;
            }
        }
        return false;
    }


    public function detectedUser(RequestInterface $request)
    {
        $cookie = $request->getCookieParams();
        if ((isset($cookie['token'])) && (!empty($cookie['token']))) {
            $user = User::getUserByParams('token', $cookie['token']);
            return $request->withAttribute('role', $user->getRole())->withAttribute('userName', $user->getName());
        } else {
            return  $request->withAttribute('role', 'guest');
        }
    }
}