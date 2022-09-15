<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DefaultValue
{
    public readonly mixed $defaultValue;

    public function __construct(mixed $defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }
}
