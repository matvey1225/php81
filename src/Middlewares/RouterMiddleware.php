<?php

namespace Matvey\Test\Middlewares;


use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RouterMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $query = $request->getQueryParams();

        if (isset($query['ctrl']) && (file_exists(__DIR__ . '/../Controllers/' . $query['ctrl'] . '.php'))) {
            $request = $request->withAttribute('ctrl', $query['ctrl']);
        } else {
            return new RedirectResponse('index.php?ctrl=Home');
        }

        if (isset($query['act']) && (!empty($query['act']))
            && method_exists('Matvey\\Test\\Controllers\\' . $query['ctrl'], $query['act']))
        {
            $request = $request->withAttribute('act', $query['act']);
        }
        return $handler->handle($request);
    }
}