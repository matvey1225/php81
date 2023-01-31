<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response\RedirectResponse;
use Matvey\Test\Container\Container;
use Matvey\Test\Models;
use Matvey\Test\Models\Role\Role;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;


class Runner implements RequestHandlerInterface
{
    public function __construct(protected Container $container)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $attributes = $request->getAttributes();
        $roleUser = $attributes['role'];
        $ctrlAttribute = $attributes['ctrlAttribute'];

        if (($roleUser !== $ctrlAttribute) && ($ctrlAttribute !== Role::GENERAL)) {
            $redirectUrl = match ($roleUser) {
                Role::GUEST => 'index.php?ctrl=Registration',
                Role::USER => 'index.php?ctrl=Home',
                Role::ADMIN => 'index.php?ctrl=NewsAdmin&act=home'
            };
            return new RedirectResponse($redirectUrl);
        }

        $ctrlStr = 'Matvey\Test\Controllers\\' . $attributes['ctrl'];
        $ctrl = ($this->container)->get($ctrlStr);
        return $ctrl->handle($request);
    }
}