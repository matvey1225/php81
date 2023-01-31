<?php

namespace Matvey\Test\Controllers;

include_once __DIR__ . '/../../vendor/autoload.php';

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Matvey\Test\Models\Role\Role;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Matvey\Test\Attributes\RoleHandlerAttribute;

#[RoleHandlerAttribute(role: Role::USER)]
class Home implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(new Stream(__DIR__ . '/../../templates/home.php'), 200, ['response' => 'ee']);
    }
}