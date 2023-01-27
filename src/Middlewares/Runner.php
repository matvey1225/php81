<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class Runner implements MiddlewareInterface
{

    /**
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $attributes = $request->getAttributes();
        $issetCtrl = (isset($attributes['ctrl']));


        if (($issetCtrl) && (($attributes['ctrl'] === "Output")||($attributes['ctrl'] === "Book"))) {
            $ctrlStr = 'Matvey\Test\Controllers\\' . $attributes['ctrl'];
            return (new  $ctrlStr)->handle($request);
        }

        $roleUser = $attributes['role'];
        $ctrlAttribute = $attributes['ctrlAttribute'];


        if ($roleUser !== $ctrlAttribute) {
            if ($roleUser === 'guest') {
                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Registration');
            }
            if ($roleUser === 'user') {
                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Home');
            }
            if ($roleUser === 'admin') {
                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=NewsAdmin&act=Home');
            }

        }

//        if (!($issetRole)) {
//
//            if (($issetCtrl) && (($attributes['ctrl'] !== 'Login') && ($attributes['ctrl'] !== 'Registration'))) {
//                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Registration');
//            }
//
//            if (!$issetCtrl) {
//                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Registration');
//            }
//        }
//
//        if (($issetRole) && (!$issetCtrl)) {
//            return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Home');
//        }
//
//        if (($issetCtrl) && ($attributes['ctrl'] == 'NewsAdmin')) {
//            if ($attributes['role'] != 'admin') {
//                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=Home');
//            }
//        }
//
//        if(($issetRole) && ($attributes['role']==='admin')){
//            if(($issetCtrl) && ($attributes['ctrl'] === 'Home')){
//                return new Response\RedirectResponse('http://homework.local/test/index.php?ctrl=NewsAdmin&act=home');
//            }
//        }


        if (($issetCtrl) && (!empty($attributes['ctrl']))) {

            $ctrlStr = 'Matvey\Test\Controllers\\' . $attributes['ctrl'];
            return (new  $ctrlStr)->handle($request);
        }

        return $handler->handle($request);
    }
}