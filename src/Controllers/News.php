<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psalm\Node\Expr\VirtualAssignRef;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: 'user')]
class News implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        $articles = Article::findAll();
//        var_dump($request->getAttributes());

        $template = TwigWorker::twig('news.html',
            ['title' => 'News', 'articles' => $articles,
            'actions' => [ ['action' => 'http://homework.local/test/index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home'] ]
        ]);

        return (new Response\HtmlResponse($template));
    }
}