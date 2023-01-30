<?php

namespace Matvey\Test\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use ReflectionClass;
use ReflectionException;


class AttributeCtrl implements MiddlewareInterface
{

    /**
     * @throws ReflectionException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $attributesRequest = $request->getAttributes();

        if ((isset($attributesRequest['ctrl'])) && (!empty($attributesRequest['ctrl']))) {
            $ctrl = 'Matvey\Test\Controllers\\' . $attributesRequest['ctrl'];
            $ctrlAttributes = (new ReflectionClass($ctrl))->getAttributes();


            if(!empty($ctrlAttributes)){
                foreach ($ctrlAttributes as $ctrlAttribute) {
                    if ($ctrlAttribute->getName() === "Matvey\Test\Attributes\RoleHandlerAttribute") {
                        $request = $request->withAttribute('ctrlAttribute', $ctrlAttribute->getArguments()['role']);
                    }
                }
            }else {
                $request = $request->withAttribute('ctrlAttribute', null);
            }

        } else {
            $request = $request->withAttribute('ctrlAttribute', null);
        }

        return $handler->handle($request);
    }
}