<?php

namespace Matvey\Test\Controllers;


use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Book\Record;
use Matvey\Test\Models\TwigWorker\TwigWorker;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[RoleHandlerAttribute(role: 'user')]
class Book implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        if ((isset($body['record'])) && (!empty($body['record']))) {
            $record = new Record();
            $record->setName($request->getAttribute('userName'))->setRecord($body['record'])->save();
            return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Book');
        }

        $template = TwigWorker::twig('book.html', [
            'title' => 'GuestBook',
            'records' => array_slice(Record::findAll(), -10),
            'input' => ['type' => 'text', 'name' => 'record', 'value' => '', 'placeholder' => 'message'],
            'onlyButton' => ['name' => '', 'value' => '', 'text' => 'Send'],
            'actions' => [['action' => 'index.php?ctrl=Home', 'method' => 'post', 'text' => 'Home']
            ]]);

        return (new Response\HtmlResponse($template));
    }
}