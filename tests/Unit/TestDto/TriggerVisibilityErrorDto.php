<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\Trigger;
use AdventureTech\DataTransferObject\DataTransferObject;

class TriggerVisibilityErrorDto extends DataTransferObject
{
    public int $number;

    #[Trigger('calculateNumber')]
    public int $calculatedNumber;

    private function calculateNumber()
    {

    }
}

