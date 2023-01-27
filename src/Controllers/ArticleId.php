<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[RoleHandlerAttribute(role: 'user')]
class ArticleId implements RequestHandlerInterface
{

    /**
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();

        $article = Article::findById($query['id']);

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