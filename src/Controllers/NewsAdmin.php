<?php

namespace Matvey\Test\Controllers;


use Exception;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Matvey\Test\Attributes\RoleHandlerAttribute;

#[RoleHandlerAttribute(role: Role::ADMIN)]
class NewsAdmin implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $attributes = $request->getAttributes();

        if ((isset($attributes['act'])) && (!empty($attributes['act']))) {
            $act = $attributes['act'];
            return $this->$act($request);

        }
        return new RedirectResponse('index.php?ctrl=NewsAdmin&act=home');
    }

    public function list(ServerRequestInterface $request): ResponseInterface
    {
        $articles = Article::findAll();
        $template = TwigWorker::twig('adminNews.html',
            [
                'title' => 'News',
                'articles' => $articles,
                'actions' => [
                    ['action' => 'index.php?ctrl=NewsAdmin&act=home', 'method' => 'post', 'text' => 'Home'],
                    ['action' => 'index.php?ctrl=NewsAdmin&act=addArticle', 'method' => 'post', 'text' => 'add article']
                ]
            ]);

        return (new Response\HtmlResponse($template));
    }


    public function addArticle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        if ((isset($body['author'])) && (isset($body['text'])) && (isset($body['header']))) {
            $article = new  Article();
            $article->setAuthor($body['author'])->setText($body['text'])->setHeader($body['header'])->save();
            return new Response\RedirectResponse('index.php?ctrl=NewsAdmin&act=list');
        }

        $template = TwigWorker::twig('adminAddArticle.html', [
            'title' => 'Add Article',
            'inputHeader' => ['text' => 'text', 'name' => 'header', 'placeholder' => 'header'],
            'textarea' => ['rows' => '5', 'cols' => '28', 'name' => 'text', 'placeholder' => 'text'],
            'inputAuthor' => ['type' => 'text', 'name' => 'author', 'placeholder' => 'author'],
            'actions' => [['action' => 'index.php?ctrl=NewsAdmin&act=home', 'method' => 'post', 'text' => 'home']]
        ]);


        return new Response\HtmlResponse($template);
    }

    /**
     * @throws Exception
     */
    public function showArticle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();
        $query = $request->getQueryParams();
        $article = Article::findById($query['id']);

        if ((isset ($body["DeleteArticle"])) && ($body["DeleteArticle"] === 'true')) {
            $article->delete();
            return new Response\RedirectResponse("index.php?ctrl=NewsAdmin&act=list");
        }

        $template = TwigWorker::twig('adminArticle.html', ['title' => $article->getHeader(),
            'article' => $article->getArticle(),
            'name' => 'DeleteArticle',
            'value' => "true",
            'actions' => [
                ['action' => 'index.php', 'method' => 'post', 'text' => 'Home'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=list', 'method' => 'post', 'text' => 'News'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=showArticle&id=' . $article->getId(), 'method' => 'post', 'text' => 'Delete article'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=editingArticle&id=' . $article->getId(), 'method' => 'post', 'text' => 'Editing Article'],
            ]
        ]);

        return new Response\HtmlResponse($template);
    }

    /**
     * @throws Test|Exception
     */
    public function editingArticle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();
        $body = $request->getParsedBody();;
        $article = new Article();
        if ((isset ($body['readyToUpdate'])) && ($body['readyToUpdate'] === 'true')) {
            $article->setText($body['text'])->setHeader($body['header'])
                ->setAuthor($body['author'])->setId($query['id'])->save();

            return new Response\RedirectResponse("index.php?ctrl=NewsAdmin&act=list");
        }

        $article = Article::findById($query['id']);

        $template = TwigWorker::twig('adminEditingArticle.html', [
            'title' => 'Editing Article',
            'article' => $article->getArticle(),
            'inputHeader' => ['type' => 'text', 'name' => 'header'],
            'textarea' => ['rows' => 5, 'cols' => 28, 'name' => 'text'],
            'inputAuthor' => ['type' => 'text', 'name' => 'author'],
            'name' => 'readyToUpdate',
            'value' => 'true',
            'actions' => ['actions' =>
                ['action' => 'index.php?ctrl=NewsAdmin&act=showArticle&id=' . $query['id'], 'method' => 'post', 'text' => 'Article'],
            ]]);

        return new  Response\HtmlResponse($template);
    }


    public function home(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(new Stream(__DIR__ . '/../../templates/adminHome.php'), 200);
    }


}