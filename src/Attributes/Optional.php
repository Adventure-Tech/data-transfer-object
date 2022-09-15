<?php declare(strict_types=1);

namespace AdventureTech\DataTransferObject\Attributes;

use Attribute;

/**
 *  Mark a DataTransferObject property as optional during instantiation.
 *  Non optional fields must be present on the source, even if null.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Optional
{
    public function __construct() {}
}
