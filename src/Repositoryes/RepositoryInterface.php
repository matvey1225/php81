<?php

namespace Matvey\Test\Repositoryes;
use Symfony\Component\Console\Helper\Table;

interface RepositoryInterface
{

    public function getById(int $id):object|null;

    public  function getAll():array;

}