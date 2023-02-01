<?php


namespace Matvey\Test\Models\Book;


use Matvey\Test\Db\Db;
use Matvey\Test\Model\Model;
use Matvey\Test\Models\Interfaces\hasId;

class Record extends Model implements hasId
{

    public const TABLE ='GuestBook';
    protected string $record;
    protected string $name;
    protected ?int $id = null;

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
        $sql = 'SELECT * FROM ' . self::TABLE;

        return Db::query($sql);
    }


    public function getId(): int|null
    {
      return $this->id;
    }
}