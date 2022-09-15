<?php

namespace AdventureTech\DataTransferObject\Tests\Unit\Units;

use AdventureTech\DataTransferObject\Attributes\DefaultValue;
use AdventureTech\DataTransferObject\Attributes\MapFrom;
use AdventureTech\DataTransferObject\Attributes\Optional;
use AdventureTech\DataTransferObject\DataTransferObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class UserDTOMissingType extends DataTransferObject
{
    public $id;

    public static function from(Model|array|stdClass $source): UserDTOMissingType
    {
        return parent::from($source);
    }
}
