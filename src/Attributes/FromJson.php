<?php

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

/**
 *  The source property is JSON and needs to be decoded.
 *  Data can optionally be cast to a DTO.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class FromJson
{
    public readonly ?string $dtoClassName;

    public function __construct(?string $dtoClassName = null)
    {
        $this->dtoClassName = $dtoClassName;
    }
}
