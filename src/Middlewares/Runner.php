<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response\RedirectResponse;
use Matvey\Test\Container\Container;
use Matvey\Test\Models\Role;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionException;


class Runner implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $attributes = $request->getAttributes();
        $issetCtrl = (isset($attributes['ctrl']));

        $roleUser = $attributes['role'];
        $ctrlAttribute = $attributes['ctrlAttribute'];

        if (($roleUser !== $ctrlAttribute)&&($ctrlAttribute !==Role::GENERAL)) {
            $redirectUrl = match ($roleUser) {
                Role::GUEST => 'index.php?ctrl=Registration',
                Role::USER => 'index.php?ctrl=Home',
                Role::ADMIN => 'index.php?ctrl=NewsAdmin&act=home'
            };

            return new RedirectResponse($redirectUrl);
        }


        if ($issetCtrl) {
            $ctrlStr = 'Matvey\Test\Controllers\\' . $attributes['ctrl'];
            $ctrl = (new Container())->get($ctrlStr);
            return $ctrl->handle($request);
        }
        return $handler->handle($request);
    }
}