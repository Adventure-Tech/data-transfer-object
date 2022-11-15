<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\DataTransferObject;

class PriceDTO extends DataTransferObject
{
    public int $price;
    public string $currency;
}