<?php
include __DIR__ . '/vendor/autoload.php';


use HttpSoft\Emitter\SapiEmitter;
use Matvey\Test\Controllers\Test as TestController;
use Matvey\Test\Middlewares\AttributeCtrl;
use Matvey\Test\Middlewares\Auth;
use Matvey\Test\Middlewares\RouterMiddleware;
use Laminas\Diactoros\Response;
use Matvey\Test\Middlewares\Runner;
use \Matvey\Test\Middlewares\Test;
use \Matvey\Test\Pipeline\Pipeline;
use \Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\ServerRequestFactory;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$log = new Logger('name');
$log->pushHandler(new StreamHandler(__DIR__ . '/log.txt', Level::Warning));
$emitter = new SapiEmitter();
$response = null;
$request = null;


$pipeline = new Pipeline(new TestController(),
    middlewares: [new RouterMiddleware(), new Auth(), new AttributeCtrl(), new Runner()]);


try {
    session_start();

    $request = (new ServerRequestFactory())::fromGlobals();
    $response = $pipeline->handle($request);

} catch (Throwable $throwable) {
    $log->warning($throwable->getMessage(), [$throwable->getCode(), $throwable->getFile(), $throwable->getFile()]);
    $response = (new TestController())->handle(new ServerRequest());
} finally {

    $emitter->emit($response);
}









