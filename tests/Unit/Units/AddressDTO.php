<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\Attributes\FromJson;
use AdventureTech\DataTransferObject\DataTransferObject;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class AddressDTO extends DataTransferObject
{
    #[FromJson]
    public string $address;

    public static function from(Model|array|stdClass $source): AddressDTO
    {
        return parent::from($source);
    }
}