<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Enum
{
    public function __construct(){}
}
