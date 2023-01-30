<?php

namespace Matvey\Test\Controllers;

include_once __DIR__ . '/../../vendor/autoload.php';

use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Matvey\Test\Attributes\RoleHandlerAttribute;
use Matvey\Test\Models\Role;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


#[RoleHandlerAttribute(role: Role::GENERAL)]
class Test implements RequestHandlerInterface
{

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(new Stream(__DIR__ . '/../../templates/test.php'), 200, ['response' => 'ee']);

    }
}