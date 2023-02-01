<?php

namespace Matvey\Test\Repositoryes;


use Matvey\Test\Db\Db;
use Matvey\Test\Models\Interfaces\hasId;
use Matvey\Test\Models\User\User;


class Repository implements RepositoryInterface
{

    public hasId $model;

    public function setModel(hasId $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function getById(int $id): hasId|null
    {
        $sql = 'SELECT * FROM ' . $this->model::TABLE . '  WHERE id = ?';
        $obj = Db::query($sql, $this->model::class, [$id]);
        return (!empty($obj)) ? $obj[0] : null;
    }

    public function getAll(): array
    {
        $sql = 'SELECT * FROM ' . $this->model::TABLE;
        return Db::query($sql);

    }

    public function getByParams(string $params, string $value): hasId|null|array
    {
        $object = Db::query('SELECT * FROM ' . $this->model::TABLE . ' WHERE ' . $params . ' = ?', $this->model::class, [$value]);
        if (empty($object)) {
            return null;
        }
        if (count($object) > 1) {
            return $object;
        }
        return $object[0];
    }


}