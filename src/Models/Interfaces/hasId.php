<?php

namespace Matvey\Test\Models\Interfaces;

interface hasId
{
    public const TABLE='';
    public function getId():int|null;
}