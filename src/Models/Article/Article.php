<?php

namespace Matvey\Test\Models\Article;

use Matvey\Test\Db\Db;
use Matvey\Test\Model\Model;

class Article extends Model
{
    public static string $table = 'News';
    protected ?int $id = null;
    protected string $text;
    protected string $author;
    protected string $header;

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setHeader(string $header): self
    {
        $this->header = $header;
        return $this;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }


    public function getId(): int|null
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getHeader(): string
    {
        return $this->header;
    }


    public static function findAll():array{
        $sql = 'SELECT * FROM ' . static::$table;
        return Db::query($sql);
    }


    public function getArticle():array{
        return ['id'=>$this->id,'header'=>$this->header,'text'=>$this->text,'author'=>$this->author];
    }


}