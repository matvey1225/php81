<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Matvey\Test\Repositoryes\RepositoryNews;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: Role::USER)]
class News implements RequestHandlerInterface
{
    protected RepositoryNews $repositoryNews;

    public function __construct(RepositoryNews $repositoryNews)
    {
        $this->repositoryNews = $repositoryNews;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $articles = $this->repositoryNews->getAll();
        $template = TwigWorker::twig('news.html',
            [
                'title' => 'News',
                'articles' => $articles,
                'actions' => [['action' => 'index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home']]
            ]);
        return new HtmlResponse($template);
    }
}