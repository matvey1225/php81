<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Middlewares\Repositories\NewsRepository;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psalm\Node\Expr\VirtualAssignRef;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: Role::USER)]
class News implements RequestHandlerInterface
{
    public NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $articles = $this->newsRepository->getNews($request);

        $template = TwigWorker::twig('news.html',
            ['title' => 'News', 'articles' => $articles,
                'actions' => [['action' => 'http://homework.local/test/index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home']]
            ]);

        return (new Response\HtmlResponse($template));
    }
}