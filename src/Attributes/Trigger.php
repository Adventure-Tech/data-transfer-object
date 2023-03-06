<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Trigger
{
    public readonly string $methodName;

    public function __construct(string $methodName)
    {
        $this->methodName = $methodName;
    }
}