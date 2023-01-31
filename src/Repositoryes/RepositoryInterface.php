<?php

namespace Matvey\Test\Repositoryes;
interface RepositoryInterface
{

    public function getById(int $id):object|null;

    public  function getAll():array;

}