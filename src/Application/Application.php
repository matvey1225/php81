<?php

namespace Matvey\Test\Application;

use Laminas\Diactoros\ServerRequest;
use Matvey\Test\Controllers\Test as TestController;
use HttpSoft\Emitter\SapiEmitter;
use Laminas\Diactoros\ServerRequestFactory;
use Matvey\Test\Container\Container;
use Matvey\Test\Middlewares\AttributeCtrl;
use Matvey\Test\Middlewares\AuthMiddleware;
use Matvey\Test\Middlewares\RouterMiddleware;
use Matvey\Test\Middlewares\Runner;
use Matvey\Test\Pipeline\Pipeline;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class Application
{
    protected Pipeline $pipeline;
    protected SapiEmitter $emitter;
    protected Container $container;
    protected Logger $logger;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */


    public function __construct()
    {
        $this->emitter = new SapiEmitter();

        $this->logger = new Logger('name');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/log.txt', Level::Warning));
        $this->container = new Container();

        $this->pipeline = new Pipeline(
            new Runner($this->container),
            middlewares: [
                new RouterMiddleware(),
                ($this->container)->get(AuthMiddleware::class),
                new AttributeCtrl()
            ]);


    }


    public function getContainer(): Container
    {
        return $this->container;
    }


    public function start(): void
    {
        try {

            session_start();
            $request = (new ServerRequestFactory())::fromGlobals();
            $response = $this->pipeline->handle($request);

        } catch (Throwable $throwable) {
            $this->logger->warning(
                $throwable->getMessage(),
                [
                    $throwable->getCode(),
                    $throwable->getFile(),
                    $throwable->getFile()
                ]
            );
            $response = (new TestController())->handle(new ServerRequest());
        } finally {
            $this->emitter->emit($response);
        }
    }
}