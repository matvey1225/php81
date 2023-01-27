<?php



namespace Matvey\Test\Attributes;


#[\Attribute]
class RoleHandlerAttribute
{

    public string $role = '';

    public function __construct(string $role){
        $this->role = $role;
    }

}