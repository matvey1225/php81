<?php

namespace Matvey\Test\Repositoryes;

use Matvey\Test\Models\Book\Record;

class RepositoryBook implements RepositoryInterface
{

    protected Record $record;

    public function __construct( Record $record)
    {
        $this->record =$record;
    }

    public function getById(int $id): object
    {
        return Record::findById($id);
    }

    public function getAll(): array
    {
        return Record::findAll();
    }
}