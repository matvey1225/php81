<?php

namespace Matvey\Test\Middlewares\Repositories;

use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Book\Record;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\RequestInterface;

class NewsRepository
{

   protected Article $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    public function getNews(RequestInterface $request): array
    {
        return Article::findAll();
    }


    public function getArticle(RequestInterface $request): ?object
    {
        $query = $request->getQueryParams();
        return Article::findById($query['id']);
    }


}