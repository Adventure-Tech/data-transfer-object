<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\DataTransferObject;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class PriceDTO extends DataTransferObject
{
    public int $price;
    public string $currency;

    public static function from(Model|array|stdClass $source): PriceDTO
    {
        return parent::from($source);
    }
}