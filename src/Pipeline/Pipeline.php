<?php


namespace Matvey\Test\Pipeline;
require_once __DIR__ . '/../../vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Pipeline implements RequestHandlerInterface
{
    /**
     * @param list<MiddlewareInterface> $middlewares
     */
    public function __construct(
        private readonly RequestHandlerInterface $handler,
        private array $middlewares,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = array_shift($this->middlewares);

        if ($middleware !== null) {
            return $middleware->process($request, $this);
        }   
        return $this->handler->handle($request);
    }
}
