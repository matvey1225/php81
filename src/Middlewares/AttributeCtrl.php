<?php

namespace Matvey\Test\Middlewares;

use Laminas\Diactoros\Response;
use Matvey\Test\Models\User\User;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class AttributeCtrl implements MiddlewareInterface
{

    /**
     * @throws \ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $attributes = $request->getAttributes();


        if((isset($attributes['ctrl'])) &&(!empty($attributes['ctrl']))){
            $ctrl = ('Matvey\Test\Controllers\\'.$attributes['ctrl']);
            $attributesCtrl= (new \ReflectionClass((new $ctrl):: class))->getAttributes();
            foreach ($attributesCtrl as $ctrlAttribute){
                if($ctrlAttribute->getName()==="Matvey\Test\Attributes\RoleHandlerAttribute"  ){
                    $request = $request->withAttribute('ctrlAttribute',$ctrlAttribute->getArguments()['role']);
                }
            }
        }else{
            $request = $request->withAttribute('ctrlAttribute',null);
        }

        return $handler->handle($request);
    }
}