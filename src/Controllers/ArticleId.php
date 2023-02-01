<?php

namespace Matvey\Test\Controllers;

use Laminas\Diactoros\Response\HtmlResponse;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Matvey\Test\Repositoryes\RepositoryArticles;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[RoleHandlerAttribute(role: Role::USER)]
class ArticleId implements RequestHandlerInterface
{

    protected RepositoryArticles $repositoryNews;

    public function __construct(RepositoryArticles $repositoryNews)
    {
        $this->repositoryNews=$repositoryNews;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();
        $article = $this->repositoryNews->getById($query['id']);
        $template = TwigWorker::twig('article.html',
            [
                'title' => $article->getHeader(),
                'article' => $article->getArticle(),
                'actions' =>
                    [
                        ['action' => 'index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home'],
                        ['action' => "index.php?ctrl=News", 'method' => 'post', 'text' => 'News']
                    ]
            ]);

        return new HtmlResponse($template);
    }
}