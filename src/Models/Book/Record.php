<?php


namespace Matvey\Test\Models\Book;


use Matvey\Test\Db\Db;
use Matvey\Test\Model\Model;

class Record extends Model
{

    protected static string $table = 'GuestBook';
    protected string $record;
    protected string $name;


    public function setName(string $name):Record
    {
        $this->name = $name;
        return $this;
    }

    public function setRecord(string $record):Record
    {
        $this->record = $record;
        return $this;
    }

    public static function findAll(): array
    {
        $sql = 'SELECT * FROM ' . static::$table;

        return Db::query($sql);
    }

}