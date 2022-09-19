<?php

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

/**
 *  The source property is JSON and needs to be decoded.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class FromJson
{
    public function __construct(){}
}
