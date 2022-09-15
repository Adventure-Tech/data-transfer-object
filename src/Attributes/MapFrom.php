<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class MapFrom
{
    public readonly string $sourcePropertyName;

    public function __construct(string $sourcePropertyName)
    {
        $this->sourcePropertyName = $sourcePropertyName;
    }
}