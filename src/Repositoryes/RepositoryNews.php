<?php

namespace Matvey\Test\Repositoryes;

use Matvey\Test\Models\Article\Article;

class RepositoryNews implements RepositoryInterface
{

    protected Article $article;

    public function __construct(Article $article)
    {
        $this->article =$article;
    }

    public function getById(int $id): object
    {
        return Article::findById($id);
    }

    public function getAll(): array
    {
        return Article::findAll();
    }
}