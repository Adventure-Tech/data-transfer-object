<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\DataTransferObject;

class DefaultValueDto extends DataTransferObject
{
    #[DefaultValue([])]
    public array $posts;
}