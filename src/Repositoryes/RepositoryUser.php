<?php

namespace Matvey\Test\Repositoryes;

use Matvey\Test\Models\User\User;

class RepositoryUser implements RepositoryInterface
{



    protected User $user;

    public function __construct(User $user)
    {
        $this->user=$user;
    }

    public function getById(int $id): object
    {
        return User::findById($id);
    }

    public function getAll(): array
    {
        return User::findAll();
    }

    public function getUserByParams(string $params ,string|int $value): object|null
    {
        return User::getUserByParams($params,$value);
    }

}