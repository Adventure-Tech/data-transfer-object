<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\DataTransferObject;

class PhoneNumbersDTO extends DataTransferObject
{
    public string $primary;
    public string $secondary;
}