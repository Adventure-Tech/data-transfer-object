<?php

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class JsonMapper
{
    public readonly ?string $rootDtoClassName;

    public function __construct(string $rootDtoClassName)
    {
        $this->rootDtoClassName = $rootDtoClassName;
    }
}