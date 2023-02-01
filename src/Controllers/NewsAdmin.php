<?php

namespace Matvey\Test\Controllers;


use Exception;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use Matvey\Test\Models\Article\Article;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Matvey\Test\Repositoryes\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Matvey\Test\Attributes\RoleHandlerAttribute;

#[RoleHandlerAttribute(role: Role::ADMIN)]
class NewsAdmin implements RequestHandlerInterface
{

    protected Repository $repositoryArticles;
    protected  Article $article;
    protected array $body;
    protected array $query;
    protected array $attributes;

    public function __construct(Repository $repositoryArticles, Article $article)
    {
        $this->repositoryArticles = $repositoryArticles->setModel($article);
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->attributes = $request->getAttributes();
        $this->body = $request->getParsedBody();
        $this->query = $request->getQueryParams();
        if (isset($this->query['id'])) {
            $this->article = (($this->repositoryArticles)->getById( (int)$this->query['id']) );
        }


        if ((isset($this->attributes['act'])) && (!empty($this->attributes['act']))) {
            $act = $this->attributes['act'];
            return $this->$act($request);
        }
        return new RedirectResponse('index.php?ctrl=NewsAdmin&act=home');
    }


    public function list(ServerRequestInterface $request): ResponseInterface
    {

        $articles = $this->repositoryArticles->getAll();


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


        if ((isset($this->body['author'])) && (isset($this->body['text'])) && (isset($this->body['header']))) {
            $this->article
                ->setAuthor($this->body['author'])
                ->setText($this->body['text'])
                ->setHeader($this->body['header'])
                ->save();
            return new RedirectResponse('index.php?ctrl=NewsAdmin&act=list');
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

        if (!(isset($this->query['id']))) {
            throw new Exception('Not found id', 404);
        }


        if ((isset ($this->body["DeleteArticle"])) && ($this->body["DeleteArticle"] === 'true')) {
            $this->article->delete();
            return new RedirectResponse("index.php?ctrl=NewsAdmin&act=list");
        }

        $template = TwigWorker::twig('adminArticle.html', ['title' => $this->article->getHeader(),
            'article' => $this->article->getArticle(),
            'name' => 'DeleteArticle',
            'value' => "true",
            'actions' => [
                ['action' => 'index.php', 'method' => 'post', 'text' => 'Home'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=list', 'method' => 'post', 'text' => 'News'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=showArticle&id=' . $this->article->getId(), 'method' => 'post', 'text' => 'Delete article'],
                ['action' => 'index.php?ctrl=NewsAdmin&act=editingArticle&id=' . $this->article->getId(), 'method' => 'post', 'text' => 'Editing Article'],
            ]
        ]);

        return new HtmlResponse($template);
    }

    /**
     * @throws Test|Exception
     */
    public function editingArticle(ServerRequestInterface $request): ResponseInterface
    {
        if (!(isset($this->query['id']))) {
            throw new Exception('Not found id', 404);
        }

        if ((isset ($this->body['readyToUpdate'])) && ($this->body['readyToUpdate'] === 'true')) {
            $this->article
                ->setText($this->body['text'])
                ->setHeader($this->body['header'])
                ->setAuthor($this->body['author'])
                ->setId($this->query['id'])
                ->save();
            return new RedirectResponse("index.php?ctrl=NewsAdmin&act=list");
        }


        $template = TwigWorker::twig('adminEditingArticle.html', [
            'title' => 'Editing Article',
            'article' => $this->article->getArticle(),
            'inputHeader' => ['type' => 'text', 'name' => 'header'],
            'textarea' => ['rows' => 5, 'cols' => 28, 'name' => 'text'],
            'inputAuthor' => ['type' => 'text', 'name' => 'author'],
            'name' => 'readyToUpdate',
            'value' => 'true',
            'actions' =>
                [
                    'actions' =>
                        [
                            'action' => 'index.php?ctrl=NewsAdmin&act=showArticle&id=' . $this->query['id'],
                            'method' => 'post',
                            'text' => 'Article'
                        ],
                ]]);

        return new  HtmlResponse($template);
    }


    public function home(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(new Stream(__DIR__ . '/../../templates/adminHome.php'), 200);
    }


}