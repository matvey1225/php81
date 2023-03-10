<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Book\Record;
use Matvey\Test\Models\Role\Role;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Matvey\Test\Repositoryes\Repository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: Role::GENERAL)]
class   Book implements RequestHandlerInterface
{

    public Repository $repositoryBook;
    public Record $record;

    public function __construct(Repository $repositoryBook,Record $record)
    {
        $this->record = $record;
        $this->repositoryBook = $repositoryBook->setModel($record);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        if ((isset($body['record'])) && (!empty($body['record']))) {
            $this->record
                ->setName($request->getAttribute('userName'))
                ->setRecord($body['record'])
                ->save();
            return new RedirectResponse('index.php?ctrl=Book');
        }



        $recordsList = array_slice($this->repositoryBook->getAll(), - 15);
        $template = TwigWorker::twig('book.html',
            [
                'title' => 'GuestBook',
                'records' =>$recordsList,
                'input' => ['type' => 'text', 'name' => 'record', 'value' => '', 'placeholder' => 'message'],
                'onlyButton' => ['name' => '', 'value' => '', 'text' => 'Send'],
                'actions' => [['action' => 'index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home']]
            ]);

        return (new Response\HtmlResponse($template));
    }
}