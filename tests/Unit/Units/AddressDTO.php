<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\Attributes\FromJson;
use AdventureTech\DataTransferObject\DataTransferObject;

class AddressDTO extends DataTransferObject
{
    #[FromJson]
    public string $address;
}