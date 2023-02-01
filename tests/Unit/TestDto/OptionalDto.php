<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\DataTransferObject;

class OptionalDto extends DataTransferObject
{
    #[Optional]
    public string $iAmOptional;
}
