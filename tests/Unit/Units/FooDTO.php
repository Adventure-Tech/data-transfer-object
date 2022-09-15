<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\DataTransferObject;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class FooDTO extends DataTransferObject
{
    public string $value1;
    public string $value2;

    public static function from(Model|array|stdClass $source): FooDTO
    {
        return parent::from($source);
    }
}