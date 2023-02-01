<?php

namespace Matvey\Test\Model;

use Exception;
use Matvey\Test\Db\Db;
use Matvey\Test\Models\Interfaces\hasId;
use Throwable;

abstract class Model implements hasId
{
    /**
     * @param int $id
     * @return object|null
     */
    public static function findById(int $id): object|null
    {
        try {
            $sql = 'SELECT * FROM ' . static::TABLE . '  WHERE id = ?';
            $obj = Db::query($sql, static::class, [$id]);
        } catch (Throwable $throwable) {
            return null;
        }

        if (empty($obj)) {
            return null;
        }

        return $obj[0];
    }

    /**
     * @return bool
     *
     * добавляет новую запись
     * @throws Exception
     */

    public function insert(): bool
    {

        $fields = get_object_vars($this);
        $cols = [];
        $data = [];

        foreach ($fields as $name => $value) {
            if ('id' == $name) {
                continue;
            }
            if (empty($value)) {
                throw new Exception('пустые поля ');
            }

            $cols[] = $name;
            $data[':' . $name] = $value;
        }
        $sql = 'INSERT INTO ' . $this::TABLE . ' (' . implode(',', $cols) . ') VALUES ('
            . implode(',', array_keys($data)) . ' )';

        return Db::execute($sql, $data);
    }

    /**
     * @return bool
     *
     * обновляет запись ранее полученной модели
     * @throws Exception
     */

    public function update(): bool
    {
        $fields = get_object_vars($this);

        $data = [];
        $sql = '';
        foreach ($fields as $name => $value) {
            $data[':' . $name] = $value;

            if ('id' == $name) {
                continue;
            }

            if (empty($value)) {
                throw new Exception('пустые поля ');
            }

            $sql = $sql . ' ' . $name . '=:' . $name . ',';
        }
        $sql = 'UPDATE ' . $this::TABLE. ' SET ' . trim($sql, ',') . '  WHERE id =:id';

        return Db::execute($sql, $data);


    }

    /**
     * @return void
     * решает что делать с объектом (добавлять или сохранять)
     * (если есть такой id - то обновит поля)
     * @throws Exception
     */

    public function save(): void
    {
        $fields = get_object_vars($this);
        if ((isset($fields['id'])) && (!empty($fields['id']))) {
            if ((bool)static::getId()) {
                static::update();
            } else {
                static::insert();
            }
        } else {
            static::insert();
        }
    }


    /**
     * @return bool|Exception
     * удаляет объект из бд по id
     */
    public function delete()
    {
        if (($this->getId()) === null){
            return new Exception(' not Id for ');
        }
        $sql = 'DELETE  FROM ' . $this::TABLE . ' WHERE id =' . $this->getId();
        return Db::execute($sql);
    }

    /**
     * @return array
     *
     * возвращает массив объектов из таблцы
     */
    public static function findAll(): array
    {
        $sql = 'SELECT * FROM ' . static::TABLE;
        return \Matvey\Php9\Db\Db::query($sql, static::class);
    }

}

