<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Middlewares\Repositories\NewsRepository;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[RoleHandlerAttribute(role: Role::USER)]
class ArticleId implements RequestHandlerInterface
{

    public NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository=$newsRepository;
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $article = $this->newsRepository->getArticle($request);

        $template = TwigWorker::twig('article.html',
            ['title' => $article->getHeader(),
                'article' => $article->getArticle(),
                'actions' => [
                    ['action' => 'http://homework.local/test/index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home'],
                    ['action' => "http://homework.local/test/index.php?ctrl=News", 'method' => 'post', 'text' => 'News']
                ]
            ]);

        return (new Response\HtmlResponse($template));
    }
}