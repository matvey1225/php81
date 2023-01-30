<?php



namespace Matvey\Test\Attributes;




use Attribute;

#[Attribute(flags: Attribute::IS_REPEATABLE)]
class RoleHandlerAttribute
{

    public string $role = '';

    public function __construct(string $role){
        $this->role = $role;
    }

}