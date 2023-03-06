<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\TestDto;

use AdventureTech\DataTransferObject\Attributes\Trigger;
use AdventureTech\DataTransferObject\DataTransferObject;

class TriggerDto extends DataTransferObject
{
    public int $number;

    #[Trigger('setTriggerValue')]
    public int $triggerValue;

    public function setTriggerValue(): void
    {
        $this->triggerValue = $this->number / 2;
    }
}

