<?php

namespace Matvey\Test\Repositoryes;

use Matvey\Test\Db\Db;
use Matvey\Test\Models\User\User;

class RepositoryUser extends Repository
{

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserByParams(string $params, string|int $value): object|null
    {
        return User::getUserByParams($params, $value);
    }

}